<?php

declare(strict_types=1);

namespace Tests\Tempest\Integration\View;

use Tempest\Http\Status;
use function Tempest\uri;
use function Tempest\view;
use Tests\Tempest\Fixtures\Controllers\TestController;
use Tests\Tempest\Fixtures\Views\ViewModel;
use Tests\Tempest\Integration\FrameworkIntegrationTestCase;

/**
 * @internal
 * @small
 */
final class ViewTest extends FrameworkIntegrationTestCase
{
    public function test_render(): void
    {
        $view = view('Views/overview.view.php')->data(name: 'Brent');

        $html = $this->render($view);

        $expected = <<<HTML
            <html lang="en"><head><title></title></head>
            <body>
                Hello Brent!
            </body></html>
            HTML;

        $this->assertStringContainsStringIgnoringLineEndings($expected, $html);
    }

    public function test_render_with_view_model(): void
    {
        $view = new ViewModel('Brent');

        $html = $this->render($view);

        $expected = <<<HTML
ViewModel Brent, 2020-01-01
HTML;

        $this->assertEquals($expected, $html);
    }

    public function test_raw_and_escaping(): void
    {
        $html = $this->render(view('Views/rawAndEscaping.php')->data(
            property: '<h1>hi</h1>',
        ));

        $expected = <<<HTML
        &lt;h1&gt;hi&lt;/h1&gt;
        <h1>hi</h1>
        HTML;

        $this->assertStringEqualsStringIgnoringLineEndings(trim($expected), trim($html));
    }

    public function test_custom_view_with_response_data(): void
    {
        $this->http
            ->get(uri([TestController::class, 'viewWithResponseData']))
            ->assertHasHeader('x-from-view')
            ->assertStatus(Status::CREATED);
    }
}
