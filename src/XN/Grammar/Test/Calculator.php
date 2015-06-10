<?php

namespace XN\Grammar\Test;

use XN\Grammar as Gr;

/**
 * @Gr\Grammar(
 * 		ignore="/\s/A",
 * 		synonyms={
 *			"primary" = @Gr\SubExpression("addSubtract"),
 *			"terminal" = @Gr\Any({
 *				@Gr\SubExpression("constant"),
 *				@Gr\SubExpression("number"),
 *				@Gr\Sequence({
 *					"(",
 *					@Gr\SubExpression("primary"),
 *					")"
 *				})
 *			})
 * 		}
 * )
 */
class Calculator
{
    /**
     * @var array
     */
    public $constants = [];

    /**
     * @Gr\Expression({
     *		@Gr\SubExpression("multiplyDivide"),
     *		@Gr\Repeated({
     *			@Gr\Any({
     *				"+",
     *				"-"
     *			}),
     *			@Gr\SubExpression("multiplyDivide")
     *		})
     * })
     */
    public function addSubtract($term0, $ops, $otherTerms)
    {
        foreach (array_map(null, $ops, $otherTerms) as $pair) {
            list($op, $term) = $pair;
            switch ($op) {
                case '+':
                    $term0 += $term;
                    break;
                case '-':
                    $term0 -= $term;
                    break;
            }
        }

        return $term0;
    }

    /**
     * @Gr\Expression({
     *		@Gr\SubExpression("power"),
     *		@Gr\Repeated({
     *			@Gr\Any({
     *				"*",
     *				"/"
     *			}),
     *			@Gr\SubExpression("power")
     *		})
     * })
     */
    public function multiplyDivide($factor0, $ops, $otherFactors)
    {
        foreach (array_map(null, $ops, $otherFactors) as $pair) {
            list($op, $factor) = $pair;
            switch ($op) {
                case '*':
                    $factor0 *= $factor;
                    break;
                case '/':
                    $factor0 /= $factor;
                    break;
            }
        }

        return $factor0;
    }

    /**
     * @Gr\Expression({
     *		@Gr\SubExpression("terminalOrCall"),
     *		@Gr\Optional({
     *			"**",
     *			@Gr\SubExpression("power")
     *		})
     * })
     */
    public function power($base, $exponent)
    {
        if ($exponent !== null) {
            $base = pow($base, $exponent);
        }

        return $base;
    }

    /**
     * @Gr\Expression({
     *		@Gr\SubExpression("terminal"),
     *		@Gr\Repeated(@Gr\SubExpression("terminal"))
     * })
     */
    public function terminalOrCall($constOrFn, $args)
    {
        if (is_callable($constOrFn)) {
            return call_user_func_array($constOrFn, $args);
        }

        return $constOrFn;
    }

    /**
     * @Gr\Expression(@Gr\Regex("~[$A-Z_][0-9A-Z_$]*~A"))
     */
    public function constant($name)
    {
        return $this->constants[strtolower($name[0])];
    }

    /**
     * @Gr\Expression(@Gr\Regex("/-?(?:0|[1-9]\d*)(?:\.\d+)?(?:[eE][+-]?\d+)?/A"))
     */
    public function number($literal)
    {
        return floatval($literal[0]);
    }
}
