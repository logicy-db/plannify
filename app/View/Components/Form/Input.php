<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Input extends Component
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
     * @var string
     */
    public $inputValue;

    /**
     * @var string
     */
    public $readonly;

    /**
     * @var string
     */
    public $additional;

    /**
     * @var string
     */
    public $lable;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $type, $placeholder, $label = null, $inputValue = null, $readonly = false, $additional = '')
    {
        $this->name = $name;
        $this->type = $type;
        $this->placeholder = $placeholder;
        $this->label = $label ?? $placeholder;
        $this->inputValue = $inputValue;
        $this->readonly = $readonly ? 'readonly' : '';
        $this->additional = $additional;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.input');
    }
}
