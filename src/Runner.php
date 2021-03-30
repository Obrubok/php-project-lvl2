<?php

namespace Gen\Diff\Runner;

use function Gen\Diff\Differ\genDiff;

function run()
{
    $doc = <<<'DOCOPT'

Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]
DOCOPT;

    $args = \Docopt::handle($doc);
    $firstFile = $args['<firstFile>'];
    $secondFile = $args['<secondFile>'];
    $diff = genDiff($firstFile, $secondFile);
    echo $diff;
}
