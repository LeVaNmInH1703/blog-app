<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testBasicExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', '1@gmail.com') // Thay thế bằng email của bạn
                ->type('password', '1@gmail.com') // Thay thế bằng mật khẩu của bạn
                ->press('Login') // Giả sử bạn có nút "Login"
                ->assertSee('Welcome'); // Thay 'Welcome' bằng tiêu đề bạn mong muốn
        });
    }
}
