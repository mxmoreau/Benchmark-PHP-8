<?php 
    /*
        $data = "47687767";
        BenchDigitMatcher::run($data, 1e3);

        - This benchmark shows us that the ctype_digit function performs better than a regex to check if a data is a number.
    */

    final class BenchDigitMatcher 
    {
        private static array $benchmark = [];

        private static function benchmark()
        {
            asort(self::$benchmark);
            return self::$benchmark;
        }



        private static function is_digit_ctype(string $data): bool
        {
            return ctype_digit($data);
        }



        private static function is_digit_regex(string $data): bool
        {
            // preg_match('/^\d+$/', $data);
            return preg_match('/^[0-9]+$/', $data);
        }



        private static function matcher(string $filter, string $data): ?bool
        {
            return match($filter){
                'digit_ctype' => ctype_digit($data),
                'digit_regex' => preg_match('/^\d+$/', $data),
                default => NULL,
            };
        }



        private static function is_digit_array_assoc(string $filter, string $data)
        {
            $array = [
                'digit_ctype' => ctype_digit($data),
                'digit_regex' => preg_match('/^\d+$/', $data),
            ];

            return isset($array[$filter]) ? $array[$filter] : NULL;
        }



        private static function is_digit_ctype_external_bench(string $data, int $n=1)
        {
            $microtime_start = microtime(1);
           
            for($i=0; $i < $n; ++$i)
            {
                self::is_digit_ctype($data);
            }

            $microtime_end = microtime(1);
            $microtime_diff = $microtime_end - $microtime_start;

            self::$benchmark[__FUNCTION__] = $microtime_diff;
        }



        private static function is_digit_ctype_internal_bench(string $data, int $n=1)
        {
            $microtime_start = microtime(1);
           
            for($i=0; $i < $n; ++$i)
            {
                ctype_digit($data);
            }

            $microtime_end = microtime(1);
            $microtime_diff = $microtime_end - $microtime_start;

            self::$benchmark[__FUNCTION__] = $microtime_diff;
        }



        private static function is_digit_regex_external_bench(string $data, int $n=1)
        {
            $microtime_start = microtime(1);
           
            for($i=0; $i < $n; ++$i)
            {
                self::is_digit_regex($data);
            }

            $microtime_end = microtime(1);
            $microtime_diff = $microtime_end - $microtime_start;

            self::$benchmark[__FUNCTION__] = $microtime_diff;
        }



        private static function is_digit_regex_internal_bench(string $data, int $n=1)
        {
            $microtime_start = microtime(1);
           
            for($i=0; $i < $n; ++$i)
            {
                preg_match('/^\d+$/', $data);
            }

            $microtime_end = microtime(1);
            $microtime_diff = $microtime_end - $microtime_start;

            self::$benchmark[__FUNCTION__] = $microtime_diff;
        }



        private static function is_digit_matcher_external_bench(string $filter, string $data, int $n)
        {
            $microtime_start = microtime(1);
           
            for($i=0; $i < $n; ++$i)
            {
               self::matcher($filter, $data);
            }

            $microtime_end = microtime(1);
            $microtime_diff = $microtime_end - $microtime_start;

            self::$benchmark[__FUNCTION__."_$filter"] = $microtime_diff;
        }



        private static function is_digit_matcher_internal_bench(string $filter, string $data, int $n=1)
        {
            $microtime_start = microtime(1);
           
            for($i=0; $i < $n; ++$i)
            {
                match($filter){
                    'digit_ctype' => ctype_digit($data),
                    'digit_regex' => preg_match('/^\d+$/', $data),
                    default => NULL,
                };
            }

            $microtime_end = microtime(1);
            $microtime_diff = $microtime_end - $microtime_start;

            self::$benchmark[__FUNCTION__."_$filter"] = $microtime_diff;
        }



        private static function is_digit_array_assoc_external_bench(string $filter, string $data, int $n=1)
        {
            $microtime_start = microtime(1);
           
            for($i=0; $i < $n; ++$i)
            {
                self::is_digit_array_assoc($filter, $data);
            }

            $microtime_end = microtime(1);
            $microtime_diff = $microtime_end - $microtime_start;

            self::$benchmark[__FUNCTION__."_$filter"] = $microtime_diff;
        }



        private static function is_digit_array_assoc_internal_bench(string $filter, string $data, int $n=1)
        {
            $microtime_start = microtime(1);
           
            for($i=0; $i < $n; ++$i)
            {
                $array = [
                    'digit_ctype' => ctype_digit($data),
                    'digit_regex' => preg_match('/^\d+$/', $data),
                ];

                isset($array[$filter]) ? $array[$filter] : NULL;
            }

            $microtime_end = microtime(1);
            $microtime_diff = $microtime_end - $microtime_start;

            self::$benchmark[__FUNCTION__."_$filter"] = $microtime_diff;
        }



        public static function run(string $data, int $n)
        {   
            // .:: CTYPE_DIGIT ::.
            self::is_digit_ctype_internal_bench($data, $n);
            self::is_digit_ctype_external_bench($data, $n);

            // .:: Regex PCRE ::.
            self::is_digit_regex_internal_bench($data, $n);
            self::is_digit_regex_external_bench($data, $n);

            // .:: Matcher (PHP 8) ::.
            self::is_digit_matcher_internal_bench('digit_ctype', $data, $n);
            self::is_digit_matcher_internal_bench('digit_regex', $data, $n);

            self::is_digit_matcher_external_bench('digit_ctype', $data, $n);
            self::is_digit_matcher_external_bench('digit_regex', $data, $n);

            // .:: Associative Array ::.
            self::is_digit_array_assoc_external_bench('digit_ctype', $data, $n);
            self::is_digit_array_assoc_external_bench('digit_regex', $data, $n);

            self::is_digit_array_assoc_internal_bench('digit_ctype', $data, $n);
            self::is_digit_array_assoc_internal_bench('digit_regex', $data, $n);

            $benchmark = self::benchmark();

            foreach($benchmark as $key => $value)
            {
                echo "<strong>$key # $n</strong><br/>$value<br/><br/>";
            }
        }
    }
?>
