<div>
    <input wire:model.lazy="foo" wire:dirty.class="foo-dirty" dusk="foo">
    <input wire:model.lazy="bar" wire:dirty.class.remove="bar-dirty" class="bar-dirty" dusk="bar">
    <span wire:dirty.class="baz-dirty" wire:target="baz" dusk="baz.target"><input wire:model.lazy="baz" dusk="baz.input"></span>
    <span wire:dirty wire:target="bob" dusk="bob.target">Dirty Indicator</span><input wire:model.lazy="bob" dusk="bob.input">

    <button type="button" dusk="dummy"></button>
</div>
