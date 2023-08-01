<?php

namespace WPForms\Pro\Migrations;

use WPForms\Migrations\UpgradeBase;
use WPForms\Tasks\Actions\Migration173Task;

/**
 * Class v1.7.3 upgrade for Pro.
 *
 * @since 1.7.5
 */
class Upgrade173 extends UpgradeBase {

	/**
	 * Run upgrade.
	 *
	 * We run migration as Action Scheduler task.
	 * Class Tasks does not exist at this point, so here we can only check task completion status.
	 *
	 * @since 1.7.5
	 *
	 * @return bool|null Upgrade result:
	 *                   true  - the upgrade completed successfully,
	 *                   false - in the case of failure,
	 *                   null  - upgrade started but not yet finished (background task).
	 */
	public function run() { // phpcs:ignore WPForms.PHP.HooksMethod.InvalidPlaceForAddingHooks

		return $this->run_async( Migration173Task::class );
	}
}
