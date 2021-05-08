<?php

namespace Actcmscss\Macros;

class ViewMacros
{
    public function extends()
    {
        return function ($view, $params = []) {
            $this->actcmscssLayout = [
                'type' => 'extends',
                'slotOrSection' => 'content',
                'view' => $view,
                'params' => $params,
            ];

            return $this;
        };
    }

    public function layout()
    {
        return function ($view, $params = []) {
            if (is_subclass_of($view, \Illuminate\View\Component::class)) {
                $layout = new $view();
                $params = array_merge($params, $layout->data());
                $view = $layout->resolveView()->name();
            }

            $this->actcmscssLayout = [
                'type' => 'component',
                'slotOrSection' => 'slot',
                'view' => $view,
                'params' => $params,
            ];

            return $this;
        };
    }

    public function layoutData()
    {
        return function ($data = []) {
            $this->actcmscssLayout['params'] = $data;

            return $this;
        };
    }

    public function section()
    {
        return function ($section) {
            $this->actcmscssLayout['slotOrSection'] = $section;

            return $this;
        };
    }

    public function slot()
    {
        return function ($slot) {
            $this->actcmscssLayout['slotOrSection'] = $slot;

            return $this;
        };
    }
}
