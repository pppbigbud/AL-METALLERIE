<?php
/**
 * Classe Loader - Gestion des hooks WordPress
 *
 * @package TrainingManager
 * @since 1.0.0
 */

namespace TrainingManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Loader
 * 
 * Enregistre tous les hooks (actions et filtres) du plugin
 */
class Loader {

    /**
     * Actions enregistrées
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Filtres enregistrés
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Ajouter une action
     *
     * @param string $hook          Nom du hook
     * @param object $component     Instance de la classe
     * @param string $callback      Méthode à appeler
     * @param int    $priority      Priorité
     * @param int    $accepted_args Nombre d'arguments
     */
    public function add_action(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Ajouter un filtre
     *
     * @param string $hook          Nom du hook
     * @param object $component     Instance de la classe
     * @param string $callback      Méthode à appeler
     * @param int    $priority      Priorité
     * @param int    $accepted_args Nombre d'arguments
     */
    public function add_filter(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Ajouter un hook à la collection
     *
     * @param array  $hooks         Collection de hooks
     * @param string $hook          Nom du hook
     * @param object $component     Instance de la classe
     * @param string $callback      Méthode à appeler
     * @param int    $priority      Priorité
     * @param int    $accepted_args Nombre d'arguments
     * @return array
     */
    private function add(array $hooks, string $hook, object $component, string $callback, int $priority, int $accepted_args): array {
        $hooks[] = [
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args,
        ];
        return $hooks;
    }

    /**
     * Enregistrer tous les hooks avec WordPress
     */
    public function run(): void {
        foreach ($this->filters as $hook) {
            add_filter(
                $hook['hook'],
                [$hook['component'], $hook['callback']],
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        foreach ($this->actions as $hook) {
            add_action(
                $hook['hook'],
                [$hook['component'], $hook['callback']],
                $hook['priority'],
                $hook['accepted_args']
            );
        }
    }
}
