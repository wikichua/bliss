<?php

namespace Wikichua\Bliss\Console\Concerns;

trait FormComponentsTrait
{
    public function formDefault($component, $fieldName, $label)
    {
        $form = <<<EOL
        <x-bliss::form-input type="$component" wire:model.defer="$fieldName" label="$label" />
        EOL;
        $formDisabled = <<<EOL
        <x-bliss::form-input type="$component" wire:model.defer="$fieldName" label="$label" disabled />
        EOL;
        $search = <<<EOL
        <x-bliss::search-input type="text" id="$fieldName" label="$label" wire:model.defer="filters.$fieldName" />
        EOL;

        return compact('form', 'formDisabled', 'search');
    }

    public function formTextarea($component, $fieldName, $label)
    {
        $form = <<<EOL
        <x-bliss::form-textarea wire:model.defer="$fieldName" label="$label" />
        EOL;
        $formDisabled = <<<EOL
        <x-bliss::form-textarea wire:model.defer="$fieldName" label="$label" disabled />
        EOL;
        $search = <<<EOL
        <x-bliss::search-input type="text" id="$fieldName" label="$label" wire:model.defer="filters.$fieldName" />
        EOL;

        return compact('form', 'formDisabled', 'search');
    }

    public function formEditor($component, $fieldName, $label)
    {
        $form = <<<EOL
        <x-bliss::form-editor wire:model.defer="$fieldName" label="$label" />
        EOL;
        $formDisabled = <<<EOL
        <x-bliss::form-editor wire:model.defer="$fieldName" label="$label" disabled />
        EOL;
        $search = null;

        return compact('form', 'formDisabled', 'search');
    }
}
