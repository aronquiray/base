<?php

namespace HalcyonLaravel\Base;

/**
 * Class BaseableOptions
 *
 * @package HalcyonLaravel\Base
 */
class BaseableOptions
{
    public $storeRules;

    public $storeRuleMessages;

    public $storeCustomAttributes;

    public $updateRules;

    public $updateRuleMessages;

    public $updateCustomAttributes;

    /**
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public static function create(): self
    {
        return (new static())->reset();
    }

    /**
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function reset(): self
    {
        $this->storeRules = null;
        $this->storeCustomAttributes = [];
        $this->storeRuleMessages = [];
        $this->updateRules = null;
        $this->updateRuleMessages = [];
        $this->updateCustomAttributes = [];

        return $this;
    }

    /**
     * @param  array  $rules
     *
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function storeRules(array $rules): self
    {
        $this->storeRules = $rules;

        return $this;
    }

    /**
     * @param  array  $storeCustomAttributes
     *
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function storeCustomAttributes(array $storeCustomAttributes): self
    {
        $this->storeCustomAttributes = $storeCustomAttributes;

        return $this;
    }

    /**
     * @param  array  $updateCustomAttributes
     *
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function updateCustomAttributes(array $updateCustomAttributes): self
    {
        $this->updateCustomAttributes = $updateCustomAttributes;

        return $this;
    }

    /**
     * @param  array  $messages
     *
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function storeRuleMessages(array $messages): self
    {
        $this->storeRuleMessages = $messages;

        return $this;
    }

    /**
     * @param  array  $rules
     *
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function updateRules(array $rules): self
    {
        $this->updateRules = $rules;

        return $this;
    }

    /**
     * @param  array  $messages
     *
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function updateRuleMessages(array $messages): self
    {
        $this->updateRuleMessages = $messages;

        return $this;
    }
}
