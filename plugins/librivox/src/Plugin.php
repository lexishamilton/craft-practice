<?php
namespace lexishamilton\craftlibrivox;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\events\RegisterUrlRulesEvent;
use craft\events\PluginEvent;
use craft\services\Plugins;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use lexishamilton\craftlibrivox\variables\LibrivoxVariable;
use lexishamilton\craftlibrivox\services\LibrivoxService;
use Yii\base\Event;

use GuzzleHttp;



/**
 * Librivox plugin
 *
 */
class Plugin extends BasePlugin
{

    /**
     * @var Plugin
     */
    public static $plugin;

    public bool $hasCpSection = true;
    public string $schemaVersion = '1.0.0';


    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    // Register menus
    public function getCpNavItem(): ?array
    {
        $item = parent::getCpNavItem();
        $item['icon'] = '';
        $item['subnav'] = [
            'index' => ['label' => 'Books', 'url' => 'librivox/index']
        ];
        return $item;
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

            // Register CP Routes
            Event::on(
                UrlManager::class,
                UrlManager::EVENT_REGISTER_CP_URL_RULES,
                function(RegisterUrlRulesEvent $event) {
                    $event->rules = array_merge($event->rules, [
                        'librivox/<bookId:\d+>' => 'librivox/book/edit',
                        'librivox/delete/<bookId:\d+>' => 'librivox/book/delete'
                    ]);
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