<?php
namespace lexishamilton\craftlibrivox;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\web\twig\variables\CraftVariable;
use lexishamilton\craftlibrivox\variables\LibrivoxVariable;
use Yii\base\Event;
use craft\events\PluginEvent;
use craft\services\Plugins;
use GuzzleHttp;
use lexishamilton\craftlibrivox\services\LibrivoxService;

/**
 * Librivox plugin
 *
 */
class Plugin extends BasePlugin
{
    public string $schemaVersion = '1.0.0';

    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();

            // register services
            $this->setComponents([

                // alias for service
                'librivox'=> LibrivoxService::class
            ]);

            // Handler: EVENT_AFTER_INSTALL_PLUGIN
            PluginEvent::on(
                Plugins::class,
                Plugins::EVENT_AFTER_INSTALL_PLUGIN,
                function (PluginEvent $event) {

                    if ($event->plugin === $this) {
                    // Do stuff here, we were just installed
                        $librivoxService = new LibrivoxService();
                        $librivoxService->loadBooks();
                    }

                }
            );

            // register variables
            Event::on(
                CraftVariable::class,
                CraftVariable::EVENT_INIT,
                function(Event $event) {
                    $variable = $event->sender;
                    $variable->set('librivox', LibrivoxVariable::class);
                }
            );

        });
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
    }
}