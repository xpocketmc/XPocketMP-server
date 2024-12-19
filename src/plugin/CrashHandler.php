<?php
namespace pocketmine\plugin;

use pocketmine\plugin\Plugin;
use pocketmine\Server;
use Throwable;

class CrashHandler{
    private static $instance;
    private $crashedPlugins = [];

    /**
     * Gets the singleton instance of the CrashHandler.
     *
     * @return self
     */
    public static function getInstance(): self{
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Handles a plugin crash by logging the error, marking the plugin as crashed,
     * and disabling it.
     *
     * @param Plugin $plugin The plugin that crashed.
     * @param Throwable $error The error that caused the crash.
     */
    public function handlePluginCrash(Plugin $plugin, Throwable $error): void{
        $this->logCrash($plugin, $error);
        $this->crashedPlugins[$plugin->getName()] = $plugin;
        PluginManager::getInstance()->disablePlugin($plugin);
        Server::getInstance()->getLogger()->warning(
            "Plugin '{$plugin->getName()}' has been disabled due to a crash: " . $error->getMessage()
        );
    }

    /**
     * Logs crash details to a file specific to the plugin.
     *
     * @param Plugin $plugin The plugin that crashed.
     * @param Throwable $error The error details.
     */
    private function logCrash(Plugin $plugin, Throwable $error): void{
        $logFile = $this->getCrashLogPath($plugin);
        file_put_contents(
            $logFile,
            "[" . date('Y-m-d H:i:s') . "] Crash: " . $error->getMessage() . PHP_EOL,
            FILE_APPEND
        );
    }

    /**
     * Gets the file path for the plugin's crash log.
     *
     * @param Plugin $plugin The plugin whose log path is required.
     * @return string
     */
    private function getCrashLogPath(Plugin $plugin): string{
        return Server::getInstance()->getDataPath() . "crash_logs/plugin_{$plugin->getName()}.log";
    }

    /**
     * Retrieves a list of plugins that have crashed.
     *
     * @return array<string, Plugin> An associative array of crashed plugins, keyed by plugin name.
     */
    public function getCrashedPlugins(): array{
        return $this->crashedPlugins;
    }

    /**
     * Resets the crash status of a plugin, allowing it to be re-enabled.
     *
     * @param Plugin $plugin The plugin whose crash status should be reset.
     */
    public function resetCrashStatus(Plugin $plugin): void{
        unset($this->crashedPlugins[$plugin->getName()]);
        $logFile = $this->getCrashLogPath($plugin);
        if (file_exists($logFile)) {
            unlink($logFile);
        }
    }
}
