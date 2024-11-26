<?php

namespace FarsiChat;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("FarsiChat plugin has been enabled!");
    }

    public function onDisable(): void {
        $this->getLogger()->info("FarsiChat plugin has been disabled!");
    }

    public function onChat(PlayerChatEvent $event): void {
        $message = $event->getMessage();
        
        // بررسی اینکه آیا متن فارسی است یا نه
        if ($this->containsFarsi($message)) {
            // اصلاح متن فارسی
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

        // اصلاح حروف فارسی برای چسباندن
        $joined = $this->joinFarsiLetters($reversed);
        return $joined;
    }

    private function joinFarsiLetters(string $text): string {
        // جدول حروف فارسی و شکل‌های مختلف آن‌ها
        $farsiMap = [
            'ا' => 'ا', 'ب' => 'ب', 'پ' => 'پ', 'ت' => 'ت',
            'ث' => 'ث', 'ج' => 'ج', 'چ' => 'چ', 'ح' => 'ح',
            'خ' => 'خ', 'د' => 'د', 'ذ' => 'ذ', 'ر' => 'ر',
            'ز' => 'ز', 'ژ' => 'ژ', 'س' => 'س', 'ش' => 'ش',
            'ص' => 'ص', 'ض' => 'ض', 'ط' => 'ط', 'ظ' => 'ظ',
            'ع' => 'ع', 'غ' => 'غ', 'ف' => 'ف', 'ق' => 'ق',
            'ک' => 'ک', 'گ' => 'گ', 'ل' => 'ل', 'م' => 'م',
            'ن' => 'ن', 'و' => 'و', 'ه' => 'ه', 'ی' => 'ی',
        ];

        // پردازش و چسباندن حروف (به صورت ساده)
        $output = '';
        $length = mb_strlen($text);

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1);
            $output .= $farsiMap[$char] ??
