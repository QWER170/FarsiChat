<?php

namespace persianchat;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("persianchat plugin has been enabled!");
    }

    public function onDisable(): void {
        $this->getLogger()->info("persianchat plugin has been disabled!");
    }

    public function onChat(PlayerChatEvent $event): void {
        $message = $event->getMessage();

        // بررسی اینکه آیا متن حاوی حروف فارسی است
        if ($this->containsFarsi($message)) {
            // اصلاح و معکوس کردن متن فارسی
            $fixedMessage = $this->fixFarsiText($message);
            $event->setMessage($fixedMessage);
        }
    }

    private function containsFarsi(string $text): bool {
        // بررسی وجود کاراکترهای فارسی در متن
        return preg_match('/[آ-ی]/u', $text) > 0;
    }

    private function fixFarsiText(string $text): string {
        // معکوس کردن متن
        $reversed = implode('', array_reverse(mb_str_split($text)));

        // اصلاح چسبندگی حروف فارسی (در صورت نیاز)
        return $this->joinFarsiLetters($reversed);
    }

    private function joinFarsiLetters(string $text): string {
        // جدول ساده چسبندگی حروف فارسی
        $lettersMap = [
            'ا' => 'ﺍ', 'ب' => 'ﺑ', 'پ' => 'ﭘ', 'ت' => 'ﺗ',
            'ث' => 'ﺛ', 'ج' => 'ﺟ', 'چ' => 'ﭼ', 'ح' => 'ﺣ',
            'خ' => 'ﺧ', 'د' => 'ﺩ', 'ذ' => 'ﺫ', 'ر' => 'ﺭ',
            'ز' => 'ﺯ', 'ژ' => 'ﮊ', 'س' => 'ﺳ', 'ش' => 'ﺷ',
            'ص' => 'ﺻ', 'ض' => 'ﺿ', 'ط' => 'ﻃ', 'ظ' => 'ﻇ',
            'ع' => 'ﻋ', 'غ' => 'ﻏ', 'ف' => 'ﻓ', 'ق' => 'ﻗ',
            'ک' => 'ﻛ', 'گ' => 'ﮔ', 'ل' => 'ﻟ', 'م' => 'ﻣ',
            'ن' => 'ﻧ', 'و' => 'ﻭ', 'ه' => 'ﻫ', 'ی' => 'ﯾ',
        ];

        $output = '';
        $length = mb_strlen($text);

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1);
            $output .= $lettersMap[$char] ?? $char; // جایگزینی حرف با نسخه چسبیده آن
        }

        return $output;
    }
}
