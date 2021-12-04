<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    /**
     * @var string
     */
    public $name;

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
    public $readonly;

    /**
     * @var array
     */
    public $options;

    /**
     * @var string
     */
    public $selectValue;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $placeholder, $selectValue, array $options = [], $readonly = false)
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->label = $placeholder;
        $this->options = $options;
        $this->selectValue = $selectValue;
        $this->readonly = $readonly ? 'readonly' : '';
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.select');
    }
}
