<?php

namespace Dof\Bundle\CharacterBundle;

class Areas
{
    const SIZE_LIMIT = 63;

    private function __construct()
    {
    }

    public static function getShape($aoe)
    {
        return $aoe[0];
    }
    public static function getParams($aoe)
    {
        return array_map('intval', explode(',', substr($aoe, 1)));
    }

    public static function getPolygons($aoe)
    {
        $shape = self::getShape($aoe);
        $params = self::getParams($aoe);
        switch (self::getShape($aoe)) {
            case AreaShape::POINT:
                return self::getPointPolygons();
            case AreaShape::CIRCLE:
            case AreaShape::RING:
            case AreaShape::NEGATED_CIRCLE:
                if ($shape == AreaShape::NEGATED_CIRCLE) {
                    array_unshift($params, 63);
                } elseif ($shape == AreaShape::RING) {
                    array_splice($params, 1, 0, [$params[0]]);
                } elseif (!isset($params[1])) {
                    $params[1] = ($shape == AreaShape::RING) ? $params[0] : 0;
                }

                return self::getCirclePolygons($params[0], $params[1]);
            case AreaShape::SQUARE:
                if (!isset($params[1])) {
                    $params[1] = 0;
                }

                return self::getSquarePolygons($params[0], $params[1]);
            case AreaShape::CROSS:
            case AreaShape::HOLLOW_CROSS:
                if (!isset($params[1])) {
                    $params[1] = ($shape == AreaShape::HOLLOW_CROSS) ? 1 : 0;
                }

                return self::getCrossPolygons($params[0], $params[1]);
            case AreaShape::DIAGONAL_CROSS:
            case AreaShape::HOLLOW_DIAGONAL_CROSS:
                if (!isset($params[1])) {
                    $params[1] = ($shape == AreaShape::HOLLOW_DIAGONAL_CROSS) ? 1 : 0;
                }

                return self::getDiagonalCrossPolygons($params[0], $params[1]);
            case AreaShape::STAR:
                if (!isset($params[1])) {
                    $params[1] = 0;
                }

                return self::getStarPolygons($params[0], $params[1]);
            case AreaShape::LINE:
                return self::getLinePolygons($params[0]);
            case AreaShape::TRANSVERSAL_LINE:
                return self::getTransversalLinePolygons($params[0]);
            case AreaShape::DIAGONAL_LINE:
                return self::getDiagonalLinePolygons($params[0]);
            case AreaShape::DIAGONAL_TRANSVERSAL_LINE:
                return self::getDiagonalTransversalLinePolygons($params[0]);
            case AreaShape::ARC:
                if (!isset($params[1])) {
                    $params[1] = 0;
                }

                return self::getArcPolygons($params[0], $params[1]);
            case AreaShape::CONE:
                if (!isset($params[1])) {
                    $params[1] = 0;
                }

                return self::getConePolygons($params[0], $params[1]);
            case AreaShape::CHECKERBOARD:
                if (!isset($params[1])) {
                    $params[1] = 0;
                }

                return self::getCheckerboardPolygons($params[0], $params[1]);
            case AreaShape::ALL0:
            case AreaShape::ALL1:
            case AreaShape::CELL:
                throw new \Exception('Not supported');
            default:
                if (AreaShape::isValid($shape)) {
                    throw new \LogicException('Not implemented');
                } else {
                    throw new \Exception('Unknown shape');
                }
        }
    }

    public static function getPointPolygons()
    {
        return [[[0, 0], [0, 1], [1, 1], [1, 0]]];
    }

    public static function getCirclePolygons($max, $min = 0)
    {
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }
        $poly0 = [];
        for ($i = 0; $i < $max; ++$i) {
            $poly0[] = [$i - $max, $i + 1];
            $poly0[] = [$i + 1 - $max, $i + 1];
        }
        $poly0[] = [0, $max + 1];
        for ($i = 0; $i < $max; ++$i) {
            $poly0[] = [$i + 1, $max + 1 - $i];
            $poly0[] = [$i + 1, $max - $i];
        }
        $poly0[] = [$max + 1, 1];
        for ($i = 0; $i < $max; ++$i) {
            $poly0[] = [$max + 1 - $i, -$i];
            $poly0[] = [$max - $i, -$i];
        }
        $poly0[] = [1, -$max];
        for ($i = 0; $i < $max; ++$i) {
            $poly0[] = [-$i, $i - $max];
            $poly0[] = [-$i, $i + 1 - $max];
        }
        $poly0[] = [-$max, 0];
        if (!$min) {
            return [$poly0];
        }
        list($poly1) = self::getCirclePolygons($min - 1, 0);

        return [$poly0, array_reverse($poly1)];
    }

    public static function getSquarePolygons($max, $min = 0)
    {
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }
        $poly0 = [[-$max, -$max], [-$max, $max + 1], [$max + 1, $max + 1], [$max + 1, -$max]];
        if (!$min) {
            return [$poly0];
        }
        list($poly1) = self::getSquarePolygons($min - 1, 0);

        return [$poly0, array_reverse($poly1)];
    }

    public static function getCrossPolygons($max, $min = 0)
    {
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }
        if ($min) {
            return [
                [[0, 1 - $min], [0, -$max], [1, -$max], [1, 1 - $min]],
                [[$min, 0], [$max + 1, 0], [$max + 1, 1], [$min, 1]],
                [[1, $min], [1, $max + 1], [0, $max + 1], [0, $min]],
                [[1 - $min, 1], [-$max, 1], [-$max, 0], [1 - $min, 0]],
            ];
        } else {
            return [[
                [0, 0], [-$max, 0], [-$max, 1],
                [0, 1], [0, $max + 1], [1, $max + 1],
                [1, 1], [$max + 1, 1], [$max + 1, 0],
                [1, 0], [1, -$max], [0, -$max],
            ]];
        }
    }

    public static function getDiagonalCrossPolygons($max, $min = 0)
    {
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }
        $polys = $min ? [] : self::getPointPolygons();
        for ($i = max($min, 1); $i <= $max; ++$i) {
            $polys[] = [[1 - $i, $i], [1 - $i, $i + 1], [-$i, $i + 1], [-$i, $i]];
            $polys[] = [[$i, $i], [$i, $i + 1], [$i + 1, $i + 1], [$i + 1, $i]];
            $polys[] = [[$i, 1 - $i], [$i, -$i], [$i + 1, -$i], [$i + 1, 1 - $i]];
            $polys[] = [[1 - $i, 1 - $i], [1 - $i, -$i], [-$i, -$i], [-$i, 1 - $i]];
        }

        return $polys;
    }

    public static function getStarPolygons($max, $min = 0)
    {
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }
        if ($max == 1) {
            return self::getSquarePolygons($max, $min);
        }
        if ($min < 2) {
            $polys = [[
                [-1, -1], [-1, 0], [-$max, 0], [-$max, 1], [-1, 1],
                [-1, 2], [0, 2], [0, 1 + $max], [1, 1 + $max], [1, 2],
                [2, 2], [2, 1], [1 + $max, 1], [1 + $max, 0], [2, 0],
                [2, -1], [1, -1], [1, -$max], [0, -$max], [0, -1],
            ]];
            if ($min) {
                list($poly1) = self::getPointPolygons();
                $polys[] = array_reverse($poly1);
            }
        } else {
            $polys = self::getCrossPolygons($max, $min);
        }

        return array_merge($polys, self::getDiagonalCrossPolygons($max, max($min, 2)));
    }

    public static function makeCastArea($minRange, $maxRange, $line, $diagonal)
    {
        $shape = $line ? ($diagonal ? '*' : 'X') : ($diagonal ? '+' : 'C');

        return $shape.$maxRange.(($minRange > 0) ? (','.$minRange) : '');
    }

    public static function getTransversalLinePolygons($max, $min = 0)
    {
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }
        if ($min) {
            return [[
                [-$max, 0], [-$min + 1, 0],
                [-$min + 1, 1], [-$max, 1],
            ], [
                [$min, 0], [$max + 1, 0],
                [$max + 1, 1], [$min, 1],
            ]];
        } else {
            return [[
                [-$max, 0], [$max + 1, 0],
                [$max + 1, 1], [-$max, 1],
            ]];
        }
    }

    public static function getLinePolygons($max, $min = 0)
    {
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }
        $poly = [
            [1, -$max], [0, -$max],
            [0, 1 - $min], [1, 1 - $min],
        ];

        return [$poly];
    }

    public static function getDiagonalLinePolygons($max, $min = 0)
    {
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }
        $polys = $min ? [] : self::getPointPolygons();
        for ($i = max($min, 1); $i <= $max; ++$i) {
            $polys[] = [[$i, 1 - $i], [$i, -$i], [$i + 1, -$i], [$i + 1, 1 - $i]];
        }

        return $polys;
    }

    public static function getDiagonalTransversalLinePolygons($max, $min = 0)
    {
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }
        $polys = $min ? [] : self::getPointPolygons();
        for ($i = max($min, 1); $i <= $max; ++$i) {
            $polys[] = [[$i, $i], [$i, $i + 1], [$i + 1, $i + 1], [$i + 1, $i]];
            $polys[] = [[1 - $i, 1 - $i], [1 - $i, -$i], [-$i, -$i], [-$i, 1 - $i]];
        }

        return $polys;
    }

    public static function getArcPolygons($max, $min = 0)
    {
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }

        $polys = $min ? [] : self::getPointPolygons();
        for ($i = max($min, 1); $i <= $max; ++$i) {
            $polys[] = [[1 - $i, $i], [1 - $i, $i + 1], [-$i, $i + 1], [-$i, $i]];
            $polys[] = [[$i, $i], [$i, $i + 1], [$i + 1, $i + 1], [$i + 1, $i]];
        }

        return $polys;
    }

    public static function getConePolygons($max, $min = 0)
    {
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }
        $poly = [];
        for ($i = $min; $i <= $max; ++$i) {
            $poly[] = [1 + $i, 1 - $i];
            $poly[] = [1 + $i, -$i];
        }
        for ($i = $min; $i <= $max; ++$i) {
            $poly[] = [$i - $max, $i - $max];
            $poly[] = [$i - $max, $i + 1 - $max];
        }

        return [$poly];
    }

    public static function getCheckerboardPolygons($max, $min = 0)
    {
        if (($max & 1) ^ ($min & 1)) {
            ++$min;
        }
        $max = min(self::SIZE_LIMIT, $max);
        if ($min > $max) {
            return [];
        }
        if (!$max) {
            return self::getPointPolygons();
        }
        $polys = $min ? [] : self::getPointPolygons();
        for ($ra = $min; $ra <= $max; $ra += 2) {
            for ($i = 0; $i < $ra; ++$i) {
                $polys[] = [[$ra - $i, $i], [$ra - $i, $i + 1], [$ra + 1 - $i, $i + 1], [$ra + 1 - $i, $i]];
                $polys[] = [[-$i, $ra - $i], [-$i, $ra + 1 - $i], [1 - $i, $ra + 1 - $i], [1 - $i, $ra - $i]];
                $polys[] = [[$i - $ra, -$i], [$i - $ra, 1 - $i], [$i + 1 - $ra, 1 - $i], [$i + 1 - $ra, -$i]];
                $polys[] = [[$i, $i - $ra], [$i, $i + 1 - $ra], [$i + 1, $i + 1 - $ra], [$i + 1, $i - $ra]];
            }
        }

        return $polys;
    }
}
