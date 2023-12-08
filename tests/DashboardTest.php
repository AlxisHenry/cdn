<?php

use App\Classes\Dashboard;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . '/../app/Classes/Dashboard.php';

class DashboardTest extends TestCase
{
	public function testDashboardSettingsStaticFunctionReturnsObject()
	{
		$this->assertIsObject(Dashboard::settings());
	}

	public function testDashboardSettingsStaticFunctionReturnsDashboardObject()
	{
		$this->assertInstanceOf(Dashboard::class, Dashboard::settings());
	}

	public function testDashboardSettingsStaticFunctionReturnsDashboardObjectWithNeededProperties()
	{
		$dashboard = Dashboard::settings();
		$this->assertObjectHasAttribute('title', $dashboard);
		$this->assertObjectHasAttribute('description', $dashboard);
	}

	public function testEnsureDashboardConfigurationFileExists()
	{
		$this->assertFileExists(__DIR__ . '/../settings.yml');
	}

	public function testDashboardSettingsStaticFunctionReturnsDashboardObjectWithNeededPropertiesAndValues()
	{
		$dashboard = Dashboard::settings();
		$this->assertObjectHasAttribute('title', $dashboard);
		$this->assertObjectHasAttribute('description', $dashboard);
		$settings = Yaml::parseFile(__DIR__ . '/../settings.yml');
		$this->assertEquals($settings['dashboard']['title'], $dashboard->getTitle());
		$this->assertEquals($settings['dashboard']['description'], $dashboard->getDescription());
	}

}