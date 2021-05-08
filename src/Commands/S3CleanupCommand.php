<?php

namespace Actcmscss\Commands;

use Illuminate\Console\Command;
use Actcmscss\FileUploadConfiguration;

class S3CleanupCommand extends Command
{
    protected $signature = 'actcmscss:configure-s3-upload-cleanup';

    protected $description = 'Configure temporary file upload s3 directory to automatically cleanup files older than 24hrs.';

    public function handle()
    {
        if (! FileUploadConfiguration::isUsingS3()) {
            $this->error("Configuration ['actcmscss.temporary_file_upload.disk'] is not set to a disk with an S3 driver.");
            return;
        }

        $adapter = FileUploadConfiguration::storage()->getDriver()->getAdapter();

        $adapter->getClient()->putBucketLifecycleConfiguration([
            'Bucket' => $adapter->getBucket(),
            'LifecycleConfiguration' => [
                'Rules' => [
                    [
                        'Prefix' => $prefix = FileUploadConfiguration::path(),
                        'Expiration' => [
                            'Days' => 1,
                        ],
                        'Status' => 'Enabled',
                    ],
                ],
            ],
        ]);

        $this->info('Actcmscss temporary S3 upload directory ['.$prefix.'] set to automatically cleanup files older than 24hrs!');
    }
}
