<?php

namespace XN\Graphics;

class ColorPseudoRepository
{
    public function find($rgb)
    {
        return new Color($rgb);
    }
    public function findOneBy($criteria)
    {
        if (isset($criteria['id'])) {
            return new Color($criteria['id']);
        } elseif (isset($criteria['rgb'])) {
            return new Color($criteria['rgb']);
        } elseif (isset($criteria['hex'])) {
            return Color::fromHex($criteria['hex']);
        } elseif (isset($criteria['red']) || isset($criteria['green']) || isset($criteria['blue'])) {
            return Color::fromRedGreenBlue(
                isset($criteria['red']) ? intval($criteria['red']) : 0,
                isset($criteria['green']) ? intval($criteria['green']) : 0,
                isset($criteria['blue']) ? intval($criteria['blue']) : 0);
        }
    }
    public function findBy($criteria)
    {
        throw new \LogicException('ColorPseudoRepository->findBy is not implemented');
    }
    public function findAll()
    {
        throw new \LogicException('ColorPseudoRepository->findAll is not implemented');
    }
}
