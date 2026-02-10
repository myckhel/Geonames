<?php

namespace MichaelDrennen\Geonames\Tests;


use MichaelDrennen\Geonames\Models\Geoname;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

class RepositoryTest extends AbstractGlobalTestCase {




    protected function getEnvironmentSetUp( $app ) {
        // Setup default database to use sqlite :memory:
        $app[ 'config' ]->set( 'database.default', 'testbench' );
        $app[ 'config' ]->set( 'database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => './tests/files/database.sqlite',
            'prefix'   => '',
        ] );
    }





    #[Group('repo')]
    #[Test]
    public function testGetStorageDirFromDatabase() {
        $dir = \MichaelDrennen\Geonames\Models\GeoSetting::getStorage();
        $this->assertEquals( $dir, 'geonames' );
    }


    #[Group('repo')]
    #[Test]
    public function testAdmin1Code() {
        $repo       = new \MichaelDrennen\Geonames\Repositories\Admin1CodeRepository();
        $admin1Code = $repo->getByCompositeKey( 'AD', '06' );
        $this->assertInstanceOf( \MichaelDrennen\Geonames\Models\Admin1Code::class, $admin1Code );

        try {
            $repo->getByCompositeKey( 'XX', '00' ); // Does not exist.
        } catch ( \Exception $exception ) {
            $this->assertInstanceOf( \Illuminate\Database\Eloquent\ModelNotFoundException::class, $exception );
        }
    }

    #[Group('repo')]
    #[Test]
    public function testAdmin2Code() {
        $repo       = new \MichaelDrennen\Geonames\Repositories\Admin2CodeRepository();
        $admin2Code = $repo->getByCompositeKey( 'AF', '08', 609 );
        $this->assertInstanceOf( \MichaelDrennen\Geonames\Models\Admin2Code::class, $admin2Code );

        try {
            $repo->getByCompositeKey( 'XX', '00', 000 ); // Does not exist.
        } catch ( \Exception $exception ) {
            $this->assertInstanceOf( \Illuminate\Database\Eloquent\ModelNotFoundException::class, $exception );
        }
    }


    #[Group('repo')]
    #[Test]
    public function testAlternateName() {
        $repo           = new \MichaelDrennen\Geonames\Repositories\AlternateNameRepository();
        $alternateNames = $repo->getByGeonameId( 7500737 );
        $this->assertInstanceOf( \Illuminate\Support\Collection::class, $alternateNames );
        $this->assertNotEmpty( $alternateNames );


        // Should be an empty Collection
        $alternateNames = $repo->getByGeonameId( 0 );
        $this->assertInstanceOf( \Illuminate\Support\Collection::class, $alternateNames );
        $this->assertEmpty( $alternateNames );

        try {
            $repo->getByGeonameId( 0 ); // Does not exist.
        } catch ( \Exception $exception ) {
            $this->assertInstanceOf( \Illuminate\Database\Eloquent\ModelNotFoundException::class, $exception );
        }
    }


    #[Group('repo')]
    #[Test]
    public function testFeatureClass() {
        $repo         = new \MichaelDrennen\Geonames\Repositories\FeatureClassRepository();
        $featureClass = $repo->getById( 'R' );
        $this->assertInstanceOf( \MichaelDrennen\Geonames\Models\FeatureClass::class, $featureClass );

        $featureClasses = $repo->all();
        $this->assertNotEmpty( $featureClasses );

        try {
            $repo->getById( 'DOESNOTEXIST' ); // Does not exist.
        } catch ( \Exception $exception ) {
            $this->assertInstanceOf( \Illuminate\Database\Eloquent\ModelNotFoundException::class, $exception );
        }
    }


    #[Group('repo')]
    #[Test]
    public function testIsoLanguageCode() {
        $repo             = new \MichaelDrennen\Geonames\Repositories\IsoLanguageCodeRepository();
        $isoLanguageCodes = $repo->all();
        $this->assertInstanceOf( \Illuminate\Support\Collection::class, $isoLanguageCodes );
        $this->assertNotEmpty( $isoLanguageCodes );
    }


    #[Group('repo')]
    #[Test]
    public function testGeoname() {
        $repo = new \MichaelDrennen\Geonames\Repositories\GeonameRepository();

        $geonames = $repo->getCitiesNotFromCountryStartingWithTerm( 'US', "ka" );
        $this->assertInstanceOf( \Illuminate\Support\Collection::class, $geonames );
        $this->assertGreaterThan( 0, $geonames->count() );
        $this->assertInstanceOf( \MichaelDrennen\Geonames\Models\Geoname::class, $geonames->first() );


        $geonames = $repo->getSchoolsFromCountryStartingWithTerm( 'UZ', "uc" );
        $this->assertInstanceOf( \Illuminate\Support\Collection::class, $geonames );
        $this->assertGreaterThan( 0, $geonames->count() );
        $this->assertInstanceOf( \MichaelDrennen\Geonames\Models\Geoname::class, $geonames->first() );


        $geonames = $repo->getCitiesFromCountryStartingWithTerm( 'UZ', 'ja' );
        $this->assertInstanceOf( \Illuminate\Support\Collection::class, $geonames );
        $this->assertGreaterThan( 0, $geonames->count() );
        $this->assertInstanceOf( \MichaelDrennen\Geonames\Models\Geoname::class, $geonames->first() );

        $geonames = $repo->getPlacesStartingWithTerm( 'Ur' );
        $this->assertInstanceOf( \Illuminate\Support\Collection::class, $geonames );
        $this->assertGreaterThan( 0, $geonames->count() );
        $this->assertInstanceOf( \MichaelDrennen\Geonames\Models\Geoname::class, $geonames->first() );

    }



}