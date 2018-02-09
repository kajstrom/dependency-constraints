<?php

namespace Test\Package;


use Test\TraitModule\TraitFile;

class FileWithTraitUseWithVisibilityOverrides
{
    use TraitFile {
        privateFunction as public;
        protectedFunction as private;
        publicFunction as protected;
    }
}