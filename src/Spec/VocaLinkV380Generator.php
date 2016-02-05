<?php

namespace Cs278\BankModulus\Spec;

final class VocaLinkV380Generator
{
    public static function generate($input, $output)
    {
        $inHandle = is_resource($input) ? $input : fopen($input, 'r');
        $outHandle = is_resource($output) ? $output : fopen($output, 'x');

        $tab = '    ';

        fwrite($outHandle, '<'."?php\n\n");
        fwrite($outHandle, sprintf("namespace %s;\n\n", __NAMESPACE__));
        fwrite($outHandle, '/'.'** @internal */'."\n");
        fwrite($outHandle, "abstract class VocaLinkV380Data\n{\n");
        fwrite($outHandle, $tab.'/'.'** @internal */'."\n");
        fwrite($outHandle, $tab.'final protected function fetchRecord($sortCode, $pass)'."\n$tab{\n");

        self::generateSimple($inHandle, $outHandle);

        fwrite($outHandle, "$tab}\n"); // Close function
        fwrite($outHandle, "}\n"); // Close class

        fclose($inHandle);
        fclose($outHandle);
    }

    private static function generateSimple($inHandle, $outHandle)
    {
        $tab = '        ';
        $seen = [];

        while ($line = trim(fgets($inHandle))) {
            $cols = preg_split('{\s+}', $line);

            $sortCodeStart = $cols[0];
            $sortCodeEnd = $cols[1];
            $algorithm = strtoupper($cols[2]);
            $weights = array_map('intval', array_slice($cols, 3, 6 + 8));
            $exception = isset($cols[17]) ? (int) $cols[17] : 0;
            $cols = null;

            if (!isset($seen[$sortCodeStart.$sortCodeEnd])) {
                $seen[$sortCodeStart.$sortCodeEnd] = 1;
            }

            fwrite($outHandle, sprintf(
                $tab.'if %s {'."\n",
                self::getComparison(
                    $seen[$sortCodeStart.$sortCodeEnd],
                    $sortCodeStart,
                    $sortCodeEnd
                )
            ));

            fwrite($outHandle, sprintf(
                $tab.'    return [%s, [%s], %u];'."\n",
                var_export($algorithm, true),
                implode(', ', $weights),
                $exception
            ));

            fwrite($outHandle, "$tab}\n\n");

            ++$seen[$sortCodeStart.$sortCodeEnd];
        }

        fseek($outHandle, ftell($outHandle) - 1); // Remove a \n
    }

    private static function getComparison($pass, $start, $end)
    {
        if ($start === $end) {
            return sprintf(
                '(%u === $pass && \'%s\' === $sortCode)',
                $pass,
                $start
            );
        }

        return sprintf(
            '(%u === $pass && \'%s\' <= $sortCode && $sortCode <= \'%s\')',
            $pass,
            $start,
            $end
        );
    }
}

// Violation of PSR1/2 but it's a dev file so sue me.
if ('cli' === PHP_SAPI && isset($_SERVER['PHP_SELF']) && __FILE__ === realpath($_SERVER['PHP_SELF'])) {
    VocaLinkV380Generator::generate(STDIN, STDOUT);
}
