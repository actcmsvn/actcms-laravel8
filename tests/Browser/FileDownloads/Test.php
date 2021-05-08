<?php

namespace Tests\Browser\FileDownloads;

use Actcmscss\Actcmscss;
use Tests\Browser\TestCase;
use Illuminate\Support\Facades\Storage;

class Test extends TestCase
{
    /** @test */
    public function trigger_downloads_from_Actcmscss_component()
    {
        $this->onlyRunOnChrome();

        $this->browse(function ($browser) {
            Actcmscss::visit($browser, Component::class)
                ->waitForActcmscss()->click('@download')
                ->waitUsing(5, 75, function () {
                    return Storage::disk('dusk-downloads')->exists('download-target.txt');
                });

            $this->assertStringContainsString(
                'I\'m the file you should download.',
                Storage::disk('dusk-downloads')->get('download-target.txt')
            );

            Actcmscss::visit($browser, Component::class)
                ->waitForActcmscss()->click('@download-quoted-disposition-filename')
                ->waitUsing(5, 75, function () {
                    return Storage::disk('dusk-downloads')->exists('download & target.txt');
                });

            $this->assertStringContainsString(
                'I\'m the file you should download.',
                Storage::disk('dusk-downloads')->get('download & target.txt')
            );

            /**
             * Trigger download with a response return.
             */
            Actcmscss::visit($browser, Component::class)
                ->waitForActcmscss()->click('@download-from-response')
                ->waitUsing(5, 75, function () {
                    return Storage::disk('dusk-downloads')->exists('download-target2.txt');
                });

            $this->assertStringContainsString(
                'I\'m the file you should download.',
                Storage::disk('dusk-downloads')->get('download-target2.txt')
            );

            Actcmscss::visit($browser, Component::class)
                ->waitForActcmscss()->click('@download-from-response-quoted-disposition-filename')
                ->waitUsing(5, 75, function () {
                    return Storage::disk('dusk-downloads')->exists('download & target2.txt');
                });

            $this->assertStringContainsString(
                'I\'m the file you should download.',
                Storage::disk('dusk-downloads')->get('download & target2.txt')
            );
        });
    }
}
