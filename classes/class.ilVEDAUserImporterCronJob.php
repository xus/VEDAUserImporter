<?php

/**
 * VEDA user importer plugin cron job class
 * @author Jesus Lopez <lopez@leifos.de>
 */
class ilVEDAUserImporterCronJob extends ilCronJob
{
	protected $plugin; // [ilCronHookPlugin]

	public function getId()
	{
		return ilVEDAUserImporterPlugin::getInstance()->getId();
	}

	public function getTitle(): string
	{
		return ilVEDAUserImporterPlugin::PNAME;
	}

	public function getDescription(): string
	{
		return ilVEDAUserImporterPlugin::getInstance()->txt("cron_job_info");
	}

	public function getDefaultScheduleType(): int
	{
		return self::SCHEDULE_TYPE_IN_HOURS;
	}

	public function getDefaultScheduleValue(): int
	{
		return ilVEDAUserImporterSettings::getInstance()->getCronInterval();
	}

	public function hasAutoActivation(): bool
	{
		return false;
	}

	public function hasFlexibleSchedule(): bool
	{
		return false;
	}

	public function hasCustomSettings(): bool
	{
		return false;
	}

	public function run(): ilCronJobResult
	{
		$result = new ilCronJobResult();

		try
		{
			$importer = new ilVEDAUserImporter();
			$importer->import();

			ilVEDAUserImporterSettings::getInstance()->updateLastCronExecution();
			$result->setStatus(ilCronJobResult::STATUS_OK);
		}
		catch(Exception $e)
		{
			$result->setStatus(ilCronJobResult::STATUS_CRASHED);
			$result->setMessage($e->getMessage());
			ilVEDAUserImporterLogger::getLogger()->write("Cron update failed with message: " . $e->getMessage());
		}

		return $result;
	}

	public function getPlugin(): ilVEDAUserImporterPlugin
	{
		return ilVEDAUserImporterPlugin::getInstance();
	}

}

?>