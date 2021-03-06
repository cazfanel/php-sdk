<?php
/**
 * Me/Password command tests.
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @author Marcelo Gornstein <marcelog@portatext.com>
 * @copyright 2015 PortaText
 */
namespace PortaText\Test;

use PortaText\Client\Base as Client;

/**
 * Me/Password command tests.
 */
class MyPasswordTest extends BaseCommandTest
{
    /**
     * @test
     */
    public function can_ask_to_reset_password()
    {
        $this->mockClientForCommand('me/password/reset', array(
            'email' => 'john@doe.com'
        ))
        ->myPassword()
        ->reset()
        ->forEmail('john@doe.com')
        ->post();
    }

    /**
     * @test
     */
    public function can_reset_password_with_a_nonce()
    {
        $this->mockClientForCommand('me/password/reset/anonce', array(
            'new_password' => 'awesomePassword!'
        ))
        ->myPassword()
        ->reset()
        ->withNonce('anonce', 'awesomePassword!')
        ->put();
    }

    /**
     * @test
     */
    public function can_change_password()
    {
        $this->mockClientForCommand('me/password', array(
            'old_password' => 'oldPassword',
            'new_password' => 'newPassword'
        ))
        ->myPassword()
        ->change('oldPassword', 'newPassword')
        ->put();
    }
}
