<?php

namespace Drupal\chatbot_api_apiai;

/**
 * Trait ApiAiContextTrait.
 *
 * API.ai supports context parameters. Intents can get/set parameters by
 * separating the context name and the parameter name with a period i.e.
 * "context_name.parameter_name". This trait will provide the method to extract
 * context and parameter names.
 *
 * @package Drupal\chatbot_api_apiai
 */
trait ApiAiContextTrait {

  /**
   * Get a context name.
   *
   * This method will exclude the parameter segment from a context (invocation)
   * name, i.e. getContextName("context_name.parameter_name") returns
   * "context_name".
   *
   * @param string $context_name
   *   The full context name syntax. It may include the parameter part.
   *
   * @return string
   *   The context name.
   */
  public function getContextName($context_name) {
    return strpos($context_name, '.') !== FALSE ? explode('.', $context_name)[0] : $context_name;
  }

  /**
   * Get a context parameter name.
   *
   * This method will extract the parameter name
   * if present in the context_name, otherwise will use the default parameter
   * name.
   *
   * @param string $context_name
   *   The full context name syntax. It may include the parameter part.
   *
   * @return string
   *   The parameter name, or the default 'value' name if no parameter part is
   *   found.
   */
  public function getParameterName($context_name) {
    return strpos($context_name, '.') !== FALSE ? explode('.', $context_name)[1] : 'value';
  }

}
