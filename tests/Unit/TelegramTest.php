<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit;

use Longman\TelegramBot\Telegram;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class TelegramTest extends TestCase
{
    /**
    * @var \Longman\TelegramBot\Telegram
    */
    private $telegram;

    /**
     * @var array A few dummy custom commands paths
     */
    private $custom_commands_paths = [
        '/tmp/php-telegram-bot-custom-commands-1',
        '/tmp/php-telegram-bot-custom-commands-2',
        '/tmp/php-telegram-bot-custom-commands-3',
    ];

    /**
    * setUp
    */
    protected function setUp(): void
    {
        $this->telegram = new Telegram('apikey', 'testbot');

        // Create a few dummy custom commands paths.
        foreach ($this->custom_commands_paths as $custom_path) {
            mkdir($custom_path);
        }
    }

    /**
     * tearDown
     */
    protected function tearDown(): void
    {
        // Clean up the custom commands paths.
        foreach ($this->custom_commands_paths as $custom_path) {
            rmdir($custom_path);
        }
    }

    public function testNewInstanceWithoutApiKeyParam()
    {
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        new Telegram(null, 'testbot');
    }

    public function testNewInstanceWithoutBotNameParam()
    {
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        new Telegram('apikey', null);
    }

    public function testGetApiKey()
    {
        $this->assertEquals('apikey', $this->telegram->getApiKey());
    }

    public function testGetBotName()
    {
        $this->assertEquals('testbot', $this->telegram->getBotName());
    }

    public function testEnableAdmins()
    {
        $tg = $this->telegram;

        $this->assertEmpty($tg->getAdminList());

        $tg->enableAdmin(1);
        $this->assertCount(1, $tg->getAdminList());

        $tg->enableAdmins([2, 3]);
        $this->assertCount(3, $tg->getAdminList());

        $tg->enableAdmin(2);
        $this->assertCount(3, $tg->getAdminList());

        $tg->enableAdmin('a string?');
        $this->assertCount(3, $tg->getAdminList());
    }

    public function testAddCustomCommandsPaths()
    {
        $tg = $this->telegram;

        $this->assertEquals(1, $tg->getCommandsPathCount());

        $tg->addCommandsPath($this->custom_commands_paths[0]);
        $this->assertEquals(2, $tg->getCommandsPathCount());

        $tg->addCommandsPath('/invalid/path');
        $this->assertEquals(2, $tg->getCommandsPathCount());

        $tg->addCommandsPaths([
            $this->custom_commands_paths[1],
            $this->custom_commands_paths[2],
        ]);
        $this->assertEquals(4, $tg->getCommandsPathCount());

        $tg->addCommandsPath($this->custom_commands_paths[0]);
        $this->assertEquals(4, $tg->getCommandsPathCount());
    }

    public function testGetCommandsList()
    {
        $commands = $this->telegram->getCommandsList();
        $this-> assertIsArray($commands);
        $this->assertNotCount(0, $commands);
    }

    public function testGetHelpCommandObject()
    {
        $command = $this->telegram->getCommandObject('help');
        $this->assertInstanceOf('Longman\TelegramBot\Commands\UserCommands\HelpCommand', $command);
    }
}
