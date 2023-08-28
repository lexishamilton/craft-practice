<?php

namespace lexishamilton\craftclearjobs;

use Craft;
use craft\base\Model;
use craft\base\Plugin as BasePlugin;
use craft\web\twig\variables\CraftVariable;
use lexishamilton\craftclearjobs\models\Settings;
use lexishamilton\craftclearjobs\services\ClearJobsService;
use GuzzleHttp;
use lexishamilton\craftclearjobs\variables\ClearJobsVariable;
use Yii\base\Event;

/**
 * Clear Jobs plugin
 *
 * @method static Plugin getInstance()
 * @method Settings getSettings()
 */
class Plugin extends BasePlugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;

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
                'clear'=> ClearJobsService::class
            ]);

            // register variables
            Event::on(
                CraftVariable::class,
                CraftVariable::EVENT_INIT,
                function(Event $event) {
                    $variable = $event->sender;
                    $variable->set('clear', ClearJobsVariable::class);
                }
            );

        });
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('_clear-jobs/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
    }
}
