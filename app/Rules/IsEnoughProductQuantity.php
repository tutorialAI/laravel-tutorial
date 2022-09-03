<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;

class IsEnoughProductQuantity implements Rule, DataAwareRule, ValidatorAwareRule
{
    private $product;

    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * The validator instance.
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Не знаю как это победить! А конкретно, очередь проверки снаружи,
        // чтобы не писать здесь ее. Программа зхапускает этот класс проверки в первую очредь
        if (!(is_numeric($value['id']) && is_numeric($value['quantity']))) {
            return false;
        }

        $this->product = $product = Product::find($value['id']);
        return $product->quantity >= $value['quantity'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $name = is_null($this->product) ? 'default' : $this->product->name;
        return "For product $name has not enough quantity to buy";
    }

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }
}
