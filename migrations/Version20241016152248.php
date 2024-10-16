<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016152248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile ADD birth_date DATETIME DEFAULT NULL, ADD fiscal_code VARCHAR(16) DEFAULT NULL, ADD birth_address_country_code VARCHAR(3) NOT NULL, ADD birth_address_administrative_area VARCHAR(32) NOT NULL, ADD birth_address_locality VARCHAR(256) DEFAULT NULL, ADD birth_address_dependent_locality VARCHAR(256) DEFAULT NULL, ADD birth_address_postal_code VARCHAR(5) DEFAULT NULL, ADD birth_address_address_line1 VARCHAR(256) DEFAULT NULL, ADD birth_address_address_line2 VARCHAR(256) DEFAULT NULL, ADD birth_address_locale VARCHAR(2) DEFAULT NULL, ADD email_address VARCHAR(180) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile DROP birth_date, DROP fiscal_code, DROP birth_address_country_code, DROP birth_address_administrative_area, DROP birth_address_locality, DROP birth_address_dependent_locality, DROP birth_address_postal_code, DROP birth_address_address_line1, DROP birth_address_address_line2, DROP birth_address_locale, DROP email_address');
    }
}
