<?php

namespace App\View\Components\Auth;

use Illuminate\View\Component;

class Field extends Component
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $placeholder;

    /**
     * @var string
     */
    public $label;

    /**
     * @var boolean
     */
    public $isRequired;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $type, $placeholder, $isRequired = true)
    {
        $this->name = $name;
        $this->type = $type;
        $this->placeholder = $placeholder;
        $this->label = $placeholder;
        $this->isRequired = $isRequired;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.auth.field');
    }
}
