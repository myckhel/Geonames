<?php

namespace MichaelDrennen\Geonames\Tests;

use MichaelDrennen\Geonames\Models\GeoSetting;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

class InstallGeoSettingTest extends BaseInstallTestCase {

    /**
     * This same code was being called repeatedly through these tests.
     */
    protected function geoSettingInstallForTest() {
        GeoSetting::install(
            [ 'BS', 'YU', 'UZ' ],
            [ 'en' ],
            'geonames',
            $this->DB_CONNECTION
        );
    }

    /**
     * This same code was being called repeatedly through these tests.
     */
    protected function geoSettingInitForTest() {
        GeoSetting::init(
            [ 'BS', 'YU', 'UZ' ],
            [ 'en' ],
            'geonames',
            $this->DB_CONNECTION
        );
    }



    #[Group('install')]
    #[Group('geosetting')]
    #[Test]
    public function testGeoSettingGetConnectionNameShouldReturnString() {
        $this->geoSettingInstallForTest();
        $connectionName = GeoSetting::getDatabaseConnectionName();
        $this->assertEquals( $this->DB_CONNECTION, $connectionName );
    }


    #[Group('install')]
    #[Group('geosetting')]
    #[Test]
    public function testGeoSettingInstall() {
        $this->geoSettingInstallForTest();
        $geoSetting = GeoSetting::first();
        $this->assertInstanceOf( GeoSetting::class, $geoSetting );
    }



    #[Group('install')]
    #[Group('geosetting')]
    #[Test]
    public function testGeoSettingInstallAfterInstall() {
        $this->geoSettingInstallForTest();
        $this->geoSettingInstallForTest();
        $geoSetting = GeoSetting::first();
        $this->assertInstanceOf( GeoSetting::class, $geoSetting );
    }


    #[Group('install')]
    #[Group('geosetting')]
    #[Test]
    public function testGeoSettingInit() {
        $this->geoSettingInitForTest();
        $geoSetting = GeoSetting::first();
        $this->assertInstanceOf( GeoSetting::class, $geoSetting );
    }



    #[Group('install')]
    #[Group('geosetting')]
    #[Test]
    public function testGeoSettingInitAfterInstall() {
        $this->geoSettingInstallForTest();
        $this->geoSettingInitForTest();
        $geoSetting = GeoSetting::first();
        $this->assertInstanceOf( GeoSetting::class, $geoSetting );
    }



    #[Group('install')]
    #[Group('geosetting')]
    #[Test]
    public function testGeoSettingAddExistingLanguageAfterInstall() {
        $this->geoSettingInstallForTest();
        GeoSetting::addLanguage( 'en', 'testing' );
        $geoSetting = GeoSetting::first();
        $this->assertInstanceOf( GeoSetting::class, $geoSetting );
        $this->assertIsArray( $geoSetting->{GeoSetting::DB_COLUMN_LANGUAGES} );
        $this->assertEquals( 'en', $geoSetting->{GeoSetting::DB_COLUMN_LANGUAGES}[ 0 ] );
    }


    #[Group('install')]
    #[Group('geosetting')]
    #[Test]
    public function testGeoSettingAddNewLanguageAfterInstall() {
        $this->geoSettingInstallForTest();
        GeoSetting::addLanguage( 'gb', 'testing' );
        $geoSetting = GeoSetting::first();
        $this->assertInstanceOf( GeoSetting::class, $geoSetting );
        $this->assertIsArray( $geoSetting->{GeoSetting::DB_COLUMN_LANGUAGES} );
        $this->assertCount( 2, $geoSetting->{GeoSetting::DB_COLUMN_LANGUAGES} );
        $this->assertEquals( 'gb', $geoSetting->{GeoSetting::DB_COLUMN_LANGUAGES}[ 1 ] );
    }


    #[Group('install')]
    #[Group('geosetting')]
    #[Test]
    public function testGeoSettingRemoveNewlyAddedLanguage() {
        $this->geoSettingInstallForTest();

        GeoSetting::addLanguage( 'gb', 'testing' );
        $geoSetting = GeoSetting::first();
        $this->assertInstanceOf( GeoSetting::class, $geoSetting );
        $this->assertIsArray( $geoSetting->{GeoSetting::DB_COLUMN_LANGUAGES} );
        $this->assertCount( 2, $geoSetting->{GeoSetting::DB_COLUMN_LANGUAGES} );
        $this->assertEquals( 'gb', $geoSetting->{GeoSetting::DB_COLUMN_LANGUAGES}[ 1 ] );

        GeoSetting::removeLanguage( 'en', 'testing' );
        $geoSetting = GeoSetting::first();
        $this->assertInstanceOf( GeoSetting::class, $geoSetting );
        $this->assertIsArray( $geoSetting->{GeoSetting::DB_COLUMN_LANGUAGES} );
        $this->assertCount( 1, $geoSetting->{GeoSetting::DB_COLUMN_LANGUAGES} );
        $this->assertEquals( 'gb', $geoSetting->{GeoSetting::DB_COLUMN_LANGUAGES}[ 0 ] );
    }



    #[Group('install')]
    #[Group('geosetting')]
    #[Test]
    public function testGeoSettingAttemptToRemoveNonexistentLanguageShouldReturnTrue() {
        $this->geoSettingInstallForTest();
        $result = GeoSetting::removeLanguage( 'xx', 'testing' );
        $this->assertTrue( $result );
    }


}