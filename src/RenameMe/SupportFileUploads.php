<?php

namespace Actcmscss\RenameMe;

use Actcmscss\Actcmscss;
use Actcmscss\TemporaryUploadedFile;
use Actcmscss\WithFileUploads;

class SupportFileUploads
{
    static function init() { return new static; }

    function __construct()
    {
        Actcmscss::listen('property.hydrate', function ($property, $value, $component, $request) {
            $uses = array_flip(class_uses_recursive($component));

            if (! in_array(WithFileUploads::class, $uses)) return;

            if (TemporaryUploadedFile::canUnserialize($value)) {
                $component->{$property} = TemporaryUploadedFile::unserializeFromActcmscssRequest($value);
            }
        });

        Actcmscss::listen('property.dehydrate', function ($property, $value, $component, $response) {
            $uses = array_flip(class_uses_recursive($component));

            if (! in_array(WithFileUploads::class, $uses)) return;

            $newValue = $this->dehydratePropertyFromWithFileUploads($value);

            if ($newValue !== $value) {
                $component->{$property} = $newValue;
            }
        });
    }

    public function dehydratePropertyFromWithFileUploads($value)
    {
        if (TemporaryUploadedFile::canUnserialize($value)) {
            return TemporaryUploadedFile::unserializeFromActcmscssRequest($value);
        }

        if ($value instanceof TemporaryUploadedFile) {
            return  $value->serializeForActcmscssResponse();
        }

        if (is_array($value) && isset(array_values($value)[0]) && array_values($value)[0] instanceof TemporaryUploadedFile && is_numeric(key($value))) {
            return array_values($value)[0]::serializeMultipleForActcmscssResponse($value);
        }

        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->dehydratePropertyFromWithFileUploads($item);
            }
        }

        return $value;
    }
}
