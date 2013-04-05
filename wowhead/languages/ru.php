<?php
/**
* Wowhead (wowhead.com) Tooltips v3 - English Language Pack
* By: Adam "craCkpot" Koch (support@wowhead-tooltips.com)
**/

/**
    Copyright (C) 2010  Adam Koch

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/

/**
 * Localization by Norrath.ru project, 2010
 * --------------------------------
 * http://norrath.ru
 * mailto:support@norrath.ru
 * All rights reserved. 
 */


$lang_array = array(
	// general errors
	'notfound'			=>	'{type} {name} не найден.',
	'curl_fail'			=>	'Не удалось получить данные, ни при помощи cURL, ни при помощи fopen (проверьте что у вас установлено одно из этих двух расширений)',	// failed to get XML
	'faction_fail'		=>	'Не удалось вывести данные о фракции {name}.',		// incompatible data
	
	// types
	'achievement'		=>	'Достижение',
	'item'				=>	'Предмет',
	'item_icon'			=>	'Предмет',
	'itemset'			=>	'Комплект',
	'spell'				=>	'Заклинание',
	'quest'				=>	'Задание',
	'profile'			=>	'Профиль',
	'craft'				=>	'Ремесло',
	'npc'				=>	'НИП',
	'zone'				=>	'Зона',
	'object'			=>	'Объект',
	'faction'			=>	'Фракция',
	'enchant'			=>	'Зачарование',
	'title'				=>	'Ранг',
	'event'				=>	'Событие',
	'pet'				=>	'Питомец',
	'stats'				=>	'Статистика',
	'race'				=>	'Раса',
	'class'				=>	'Класс',
	
	/*
	 * Armory Module
	 */
	// armory and guild
	'armory_blocked'	=>	'Возникла проблема при выполнении запроса. Возможно доступ к Оружейной блокирован.',
	'char_not_found'	=>	'Персонаж не найден.',
	'char_no_data'		=>	'Данные о персонаже недоступны.',
	'guild_not_found'	=>	'Возникла проблема при выполнении запроса. Гильдия не найдена.',
	
	// misc area
	'achievements'		=>	'Достижения',
	'achievement_pts'	=>	'Очки достижений',
	'avg_ilevel'		=>	'Средний уровень одетых предметов',
	'lifetime_hk'		=>	'Всего почетных побед',
	
	// base stats
	'stamina'			=>	'Выносливость',
	'intellect'			=>	'Интеллект',
	'strength'			=>	'Сила',
	'agility'			=>	'Ловкость',
	'spirit'			=>	'Дух',
	'armor'				=>	'Броня',
	
	// spell damage and crit
	'arcane_dmg'		=>	'Урон тайной магией',
	'arcane_crit'		=>	'Шанс крита тайной',
	'fire_dmg'			=>	'Урон огненной магией',
	'fire_crit'			=>	'Шанс крита огненной',
	'frost_dmg'			=>	'Урон магией льда',
	'frost_crit'		=>	'Шанс крита ледяной',
	'shadow_dmg'		=>	'Урон магией тьмы',
	'shadow_crit'		=>	'Шанс крита тёмной',
	'holy_dmg'			=>	'Урон магией света',
	'holy_crit'			=>	'Шанс крита светлой',
	'nature_dmg'		=>	'Урон силами природы',
	'nature_crit'		=>	'Шанс крита природной',
	
	// spell hit, haste, and penetration
	'spell_hit'			=>	'Шанс попасть заклинанием',
	'haste'				=>	'Рейтинг скорости боя',		// spell, ranged, or melee
	'spell_pen'			=>	'Проникающая способность заклинаний',
	
	// melee shizzle
	'melee_main_dmg'	=>	'Урон ближн. прав.рукой',
	'melee_main_dps'	=>	'УВС ближн. прав.рукой',
	'melee_main_speed'	=>	'Скорость ближн. прав.рукой',
	'melee_off_dmg'		=>	'Урон ближн. лев.рукой',
	'melee_off_dps'		=>	'УВС ближн. лев.рукой',
	'melee_off_speed'	=>	'Скорость ближн. лев.рукой',
	'melee_power'		=>	'Сила атаки ближн. боя',
	'melee_hit'			=>	'Шанс попасть по цели оружием ближн. боя',
	'melee_crit'		=>	'Критический удар',
	'melee_expertise'	=>	'Мастерство',
	
	// tanking stats
	'defense'			=>	'Защита',
	'parry_chance'		=>	'Шанс парировать',
	'dodge_chance'		=>	'Шанс увернутся',
	'block_chance'		=>	'Шанс заблокировать',
	'resilience'		=>	'Устойчивость',
	
	// healing and associated stats
	'healing'			=>	'Лечение',
	'mana_regen'		=>	'Восстановление маны',
	'mana_regen_cast'	=>	'Восстановление маны (при чтении заклинаний)',
	
	// ranged stats
	'ranged_dmg'		=>	'Урон в дальн.б.',
	'ranged_dps'		=>	'УВС в дальн.б.',
	'ranged_speed'		=>	'Скорость дальн.б.',
	'ranged_hit'		=>	'Шанс попасть в дальн.б.',
	'ranged_crit'		=>	'Шанс крит.урона в дальн.б.',
	'ranged_power'		=>	'Сила атаки дальн.б.',
	
	// recruit
	'already_used'		=>	'Тэг анкеты уже использован на этой странице. Разрешается использовать не более одной анкеты на странице.',
	'invalid_xml'		=>	'Ошибка XML. Попробуйте еще раз.',
	'untalented'		=>	'Таланты отсутствуют.',
	'no_char_breakdown'	=>	'Очки не распределены по талантам.',
	
	// gear slots
	'ammo'				=>	'Ammo',
	'head'				=>	'Голова',
	'neck'				=>	'Шея',
	'shoulder'			=>	'Плечи',
	'shirt'				=>	'Рубашка',
	'chest'				=>	'Грудь',
	'belt'				=>	'Пояс',
	'legs'				=>	'Ноги',
	'feet'				=>	'Ступни',
	'wrist'				=>	'Запястья',
	'gloves'			=>	'Кисть руки',
	'ring1'				=>	'Палец',
	'ring2'				=>	'Палец',
	'trinket1'			=>	'Аксессуар',
	'trinket2'			=>	'Аксессуар',
	'back'				=>	'Спина',
	'main_hand'			=>	'Правая рука',
	'off_hand'			=>	'Левая рука',
	'ranged'			=>	'Дальн.бой',
	'tabard'			=>	'Гербовая накидка',
	
	// reputation
	'hated'				=>	'Ненависть',
	'hostile'			=>	'Враждебность',
	'unfriendly'		=>	'Неприязнь',
	'neutral'			=>	'Равнодушие',
	'friendly'			=>	'Дружелюбие',
	'honored'			=>	'Уважение',
	'revered'			=>	'Почтение',
	'exalted'			=>	'Превознесение',
	
	/**
	 * Talent Tree Names
	 */
	// death knight
	'deathknight_1'		=>	'Кровь',
	'deathknight_2'		=>	'Лед',
	'deathKnight_3'		=>	'Нечестивость',

	// druid
	'druid_1'			=>	'Баланс',
	'druid_2'			=>	'Сила зверя',
	'druid_3'			=>	'Исцеление',
	
	// hunter
	'hunter_1'			=>	'Повелитель зверей',
	'hunter_2'			=>	'Стрельба',
	'hunter_3'			=>	'Выживание',
	
	// mage
	'mage_1'			=>	'Тайная магия',
	'mage_2'			=>	'Огонь',
	'mage_3'			=>	'Лед',
	
	// paladin
	'paladin_1'			=>	'Свет',
	'paladin_2'			=>	'Защита',
	'paladin_3'			=>	'Воздаяние',
	
	// priest
	'priest_1'			=>	'Послушание',
	'priest_2'			=>	'Свет',
	'priest_3'			=>	'Тьма',
	
	// rogue
	'rogue_1'			=>	'Ликвидация',
	'rogue_2'			=>	'Бой',
	'rogue_3'			=>	'Скрытность',
	
	// shaman
	'shaman_1'			=>	'Стихии',
	'shaman_2'			=>	'Совершенствование',
	'shaman_3'			=>	'Исцеление',
	
	// warlock
	'warlock_1'			=>	'Колдовство',
	'warlock_2'			=>	'Демонология',
	'warlock_3'			=>	'Разрушение',
	
	// warrior
	'warrior_1'			=>	'Оружие',
	'warrior_2'			=>	'Неистовство',
	'warrior_3'			=>	'Защита'
);
?>