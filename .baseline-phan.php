<?php
/**
 * This is an automatically generated baseline for Phan issues.
 * When Phan is invoked with --load-baseline=path/to/baseline.php,
 * The pre-existing issues listed in this file won't be emitted.
 *
 * This file can be updated by invoking Phan with --save-baseline=path/to/baseline.php
 * (can be combined with --load-baseline)
 */
return [
    // # Issue statistics:
    // PhanTypeMismatchArgument : 25+ occurrences
    // PhanParamSignatureRealMismatchHasNoParamType : 10+ occurrences
    // PhanUnreferencedProtectedProperty : 2 occurrences
    // PhanParamSignatureMismatch : 1 occurrence
    // PhanTypeMismatchPropertyProbablyReal : 1 occurrence
    // PhanUndeclaredMethod : 1 occurrence

    // Currently, file_suppressions and directory_suppressions are the only supported suppressions
    'file_suppressions' => [
        'src/Command/SerendipityHQStylesCommand.php' => ['PhanUnreferencedProtectedProperty'],
        'src/Command/SymfonyStylesCommand.php' => ['PhanUnreferencedProtectedProperty'],
        'src/Console/Style/SerendipityHQStyleSF5.php' => ['PhanParamSignatureMismatch', 'PhanParamSignatureRealMismatchHasNoParamType', 'PhanTypeMismatchArgument', 'PhanTypeMismatchPropertyProbablyReal', 'PhanUndeclaredMethod'],
    ],
    // 'directory_suppressions' => ['src/directory_name' => ['PhanIssueName1', 'PhanIssueName2']] can be manually added if needed.
    // (directory_suppressions will currently be ignored by subsequent calls to --save-baseline, but may be preserved in future Phan releases)
];
