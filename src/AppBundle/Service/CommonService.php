<?php

namespace AppBundle\Service;

class CommonService
{
    public function mbSubstrReplace($str, $replace, $start, $num = null)
    {
        // 文字数取得
        $strNum = mb_strlen($str);

        if ($start < 0) {
            // 負の値だったら終端からの位置
            $start = $strNum + $start;
            if ($start < 0) {
                // 終端からの位置が負の値になるなら「0」にする
                $start = 0;
            }
        }

        // 置換える箇所の「前」を取得
        $tempBefore = mb_substr($str, 0, $start);

        if ($start > $strNum) {
            // 開始位置が文字数より多い
            $num = $strNum;
        }

        if (!isset($num)) {
            // 文字数の指定無し
            $num = $strNum;
        } elseif ($num < 0) {
            // 負の値だったら置き換えない終端までの文字数
            $num = $strNum + $num;
            if ($num < 0) {
                // 「置き換えない終端までの文字数」が負の値になる場合
                $num = $start - $strNum;
            }
        } else {
            $num = $start + $num;
        }

        // 置換える箇所の「後」を取得
        $tempAfter = mb_substr($str, $num, $strNum);

        // 置換えの文字と合体
        return $tempBefore.$replace.$tempAfter;
    }

    public function enableHtmlLink(array $tweets, $links = true, $users = true, $hashtags = true)
    {
        foreach ($tweets as $index => $tweet) {
            $text = $tweet['text'];

            $entities = [];

            if ($links && is_array($tweet['entities']['urls'])) {
                foreach ($tweet['entities']['urls'] as $e) {
                    $temp['start'] = $e['indices'][0];
                    $temp['end'] = $e['indices'][1];
                    $temp['replacement'] = "<a href='".$e['expanded_url']."' target='_blank'>".$e['display_url'].'</a>';
                    $entities[] = $temp;
                }
            }
            if ($users && is_array($tweet['entities']['user_mentions'])) {
                foreach ($tweet['entities']['user_mentions'] as $e) {
                    $temp['start'] = $e['indices'][0];
                    $temp['end'] = $e['indices'][1];
                    $temp['replacement'] = "<a href='https://twitter.com/".$e['screen_name']."' target='_blank'>@".$e['screen_name'].'</a>';
                    $entities[] = $temp;
                }
            }
            if ($hashtags && is_array($tweet['entities']['hashtags'])) {
                foreach ($tweet['entities']['hashtags'] as $e) {
                    $temp['start'] = $e['indices'][0];
                    $temp['end'] = $e['indices'][1];
                    $temp['replacement'] = "<a href='https://twitter.com/hashtag/".$e['text']."?src=hash' target='_blank'>#".$e['text'].'</a>';
                    $entities[] = $temp;
                }
            }

            usort(
                $entities,
                function ($a, $b) {
                    return $b['start'] - $a['start'];
                }
            );

            foreach ($entities as $item) {
                $text = $this->mbSubstrReplace($text, $item['replacement'], $item['start'], $item['end'] - $item['start']);
            }

            $tweets[$index]['text'] = $text;
        }

        return $tweets;
    }
}
