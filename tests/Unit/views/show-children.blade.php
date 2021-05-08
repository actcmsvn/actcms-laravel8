<div>
    @foreach ($children as $child)
        @Actcmscss('child', ['name' => $child], key($child))
    @endforeach
</div>
