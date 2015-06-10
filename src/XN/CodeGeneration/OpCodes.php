<?php

namespace XN\CodeGeneration;

use XN\Common\Enum;

class OpCodes extends Enum
{
    const ADD = 1;
    const ARROW = 68;
    const BAND = 2;
    const BNOT = 3;
    const BOR = 4;
    const BR = 5;
    const BRFALSE = 6;
    const BRTRUE = 7;
    const BXOR = 8;
    const CALL = 9;
    const CALLI = 54;
    const CALLMETHOD = 10;
    const CALLMETHODI = 55;
    const CALLSTATIC = 56;
    const CALLSTATICI = 57;
    const CALLSTATICCI = 58;
    const CALLSTATICCII = 59;
    const CEQ = 11;
    const CGE = 12;
    const CGT = 13;
    const CLE = 14;
    const CLT = 15;
    const CNE = 16;
    const CONV = 17;
    const CSTRICTEQ = 18;
    const CSTRICTNE = 19;
    const DIV = 20;
    const DUP = 21;
    const ISINST = 22;
    const ISINSTI = 66;
    const LDCONST = 23;
    const LDCONSTCI = 67;
    const LDELEM = 24;
    const LDFLD = 25;
    const LDFLDI = 26;
    const LDSFLD = 27;
    const LDSFLDI = 28;
    const LDSFLDCI = 60;
    const LDSFLDCII = 61;
    const LDVAL = 29;
    const LDVAR = 30;
    const LDVARI = 31;
    const MKREF = 64;
    const MUL = 32;
    const NEG = 33;
    const NEWARR = 50;
    const NEWOBJ = 51;
    const NEWOBJI = 52;
    const NOT = 49;
    const POP = 34;
    const POWER = 65;
    const QUIET = 71;
    const REM = 35;
    const RET = 36;
    const SHL = 37;
    const SHR = 38;
    const SPREAD = 53;
    const STELEM = 39;
    const STFLD = 40;
    const STFLDI = 41;
    const STNEWELEM = 69;
    const STRCAT = 70;
    const STSFLD = 42;
    const STSFLDI = 43;
    const STSFLDCI = 62;
    const STSFLDCII = 63;
    const STVAR = 44;
    const STVARI = 45;
    const SUB = 46;
    const THROWEX = 47;
    const TRYEX = 48;

    public static function isValid($opCode)
    {
        return $opCode >= 1 && $opCode <= 71;
    }
}
