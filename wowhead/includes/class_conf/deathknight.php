<?php

$stats_conf_enable = Array(
                            /* --base stats-- */
                                'stamina',
                                'strength',
                            //    'intellect',
                            //    'agility',
                            //    'spirit',
                            //    'armor',
                            /*   -- spell damage--   */
                            //     'shadow_power',
                            //     'shadow_crit',
                            //     'fire_power',
                            //     'fire_crit',
                            //     'frost_power',
                            //     'frost_crit',
                            //     'arcane_power',
                            //     'arcane_crit',
                            //     'nature_power',
                            //     'nature_crit',
                            //     'holy_power',
                            //     'holy_crit',
                            //     'healing',
                            //     'mana_regen',
                            //     'mana_regen_cast',
                            //     'spell_hit',
                            //     'penetration',
							//	   'haste_rating',
                            /*   -- melee damage--   */
                                   'melee_main_dmg',
                            //       'melee_main_speed',
                                   'melee_main_dps',
                            //       'melee_off_dmg',
                            //       'melee_off_speed',
                            //       'melee_off_dps',
                            //       'melee_power',
                                   'melee_hit',
                            //       'melee_crit',
                                   'melee_expertise',
				   'haste_rating',
                            /*   -- ranged damage--   */
                            //       'ranged_power',
                            //       'ranged_dmg',
                            //       'ranged_speed',
                            //       'ranged_dps',
                            //       'ranged_crit',
                            //       'ranged_hit',
                            /*  --defenses--  */
                            //       'defense',
                            //       'dodge',
                            //       'parry',
//                                   'block',
//                                   'resilience',
                            /*  --resistances--  */
                            //       'arcane_resist',
                            //       'fire_resist',
                            //       'frost_resist',
                            //       'shadow_resist',
                            //       'nature_resist',
                            //       'holy_resist',
);

$this->enable_stats ($stats_conf_enable);

switch ($this->main_spec) {
case 1: // Blood
	$this->enable_stats (array ('melee_power', 'armor', 'parry'));
	break;
case 2: // Frost
        $this->enable_stats (array ('melee_crit', 'melee_off_dps', 'melee_main_speed'));
	break;
case 3: // Unholy
        $this->enable_stats (array ('melee_power', 'agility'));
	break;
}

?>
