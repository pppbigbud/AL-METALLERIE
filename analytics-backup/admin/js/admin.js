/**
 * AL Métallerie Analytics - Admin JavaScript
 */

(function($) {
    'use strict';

    // ===========================================
    // INITIALISATION
    // ===========================================
    $(document).ready(function() {
        initDarkMode();
        initPeriodSelector();
        initCharts();
        loadTopPages();
    });

    // ===========================================
    // DARK MODE
    // ===========================================
    function initDarkMode() {
        const darkModeBtn = $('#dark-mode-toggle');
        const isDark = localStorage.getItem('almetal_dark_mode') === 'true';
        
        if (isDark) {
            $('body').addClass('almetal-dark-mode');
        }
        
        darkModeBtn.on('click', function() {
            $('body').toggleClass('almetal-dark-mode');
            localStorage.setItem('almetal_dark_mode', $('body').hasClass('almetal-dark-mode'));
        });
    }

    // ===========================================
    // PERIOD SELECTOR
    // ===========================================
    function initPeriodSelector() {
        $('#period-selector').on('change', function() {
            const period = $(this).val();
            window.location.href = updateQueryParam('period', period);
        });
    }

    function updateQueryParam(key, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(key, value);
        return url.toString();
    }

    // ===========================================
    // CHARTS
    // ===========================================
    function initCharts() {
        if (typeof Chart === 'undefined' || typeof almetalChartData === 'undefined') {
            return;
        }

        // Charger les données
        loadTrafficChart();
        loadSourcesChart();
        loadDevicesChart();
    }

    function loadTrafficChart() {
        const ctx = document.getElementById('traffic-chart');
        if (!ctx) return;

        fetch(almetalAnalyticsAdmin.restUrl + 'stats/visits?period=' + almetalChartData.period, {
            headers: { 'X-WP-Nonce': almetalAnalyticsAdmin.nonce }
        })
        .then(response => response.json())
        .then(data => {
            const labels = data.map(d => formatDate(d.date));
            const visits = data.map(d => d.visits);
            const uniqueVisitors = data.map(d => d.unique_visitors);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Visites',
                            data: visits,
                            borderColor: '#F08B18',
                            backgroundColor: 'rgba(240, 139, 24, 0.1)',
                            fill: true,
                            tension: 0.4,
                        },
                        {
                            label: 'Visiteurs uniques',
                            data: uniqueVisitors,
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            fill: true,
                            tension: 0.4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    }

    function loadSourcesChart() {
        const ctx = document.getElementById('sources-chart');
        if (!ctx) return;

        fetch(almetalAnalyticsAdmin.restUrl + 'stats/sources?period=' + almetalChartData.period, {
            headers: { 'X-WP-Nonce': almetalAnalyticsAdmin.nonce }
        })
        .then(response => response.json())
        .then(data => {
            const labels = data.map(d => d.source);
            const values = data.map(d => d.visits);
            const colors = [
                '#F08B18', '#667eea', '#43e97b', '#f5576c', 
                '#4facfe', '#fa709a', '#fee140', '#30cfd0'
            ];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors.slice(0, labels.length),
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                            }
                        }
                    }
                }
            });
        });
    }

    function loadDevicesChart() {
        const ctx = document.getElementById('devices-chart');
        if (!ctx) return;

        fetch(almetalAnalyticsAdmin.restUrl + 'stats/devices?period=' + almetalChartData.period, {
            headers: { 'X-WP-Nonce': almetalAnalyticsAdmin.nonce }
        })
        .then(response => response.json())
        .then(data => {
            const labels = data.map(d => d.device_type.charAt(0).toUpperCase() + d.device_type.slice(1));
            const values = data.map(d => d.count);
            const colors = {
                'Desktop': '#667eea',
                'Mobile': '#F08B18',
                'Tablet': '#43e97b'
            };

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: labels.map(l => colors[l] || '#ccc'),
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                            }
                        }
                    }
                }
            });
        });
    }

    // ===========================================
    // TOP PAGES TABLE
    // ===========================================
    function loadTopPages() {
        const table = $('#top-pages-table tbody');
        if (!table.length || typeof almetalChartData === 'undefined') return;

        fetch(almetalAnalyticsAdmin.restUrl + 'stats/pages?period=' + almetalChartData.period + '&limit=10', {
            headers: { 'X-WP-Nonce': almetalAnalyticsAdmin.nonce }
        })
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                table.html('<tr><td colspan="4" class="almetal-no-data">' + almetalAnalyticsAdmin.strings.noData + '</td></tr>');
                return;
            }

            const rows = data.map(page => {
                const url = page.page_url.length > 40 ? page.page_url.substring(0, 40) + '...' : page.page_url;
                const title = page.page_title || url;
                const duration = formatDuration(page.avg_duration);
                
                return `
                    <tr>
                        <td title="${page.page_url}">${title}</td>
                        <td>${page.views}</td>
                        <td>${page.unique_visitors}</td>
                        <td>${duration}</td>
                    </tr>
                `;
            }).join('');

            table.html(rows);
        })
        .catch(error => {
            table.html('<tr><td colspan="4" class="almetal-no-data">' + almetalAnalyticsAdmin.strings.error + '</td></tr>');
        });
    }

    // ===========================================
    // HELPERS
    // ===========================================
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
    }

    function formatDuration(seconds) {
        if (!seconds || seconds < 1) return '0:00';
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return mins + ':' + (secs < 10 ? '0' : '') + secs;
    }

})(jQuery);
