<div>
    @isset($params)
        @Actcmscss($component, $params)
    @else
        @Actcmscss($component)
    @endisset
</div>
