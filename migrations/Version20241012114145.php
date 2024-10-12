<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241012114145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groups (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE medical_data (id BINARY(16) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE person (id BINARY(16) NOT NULL, medical_data_id BINARY(16) DEFAULT NULL, discr VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, profile_id BINARY(16) DEFAULT NULL, is_adult TINYINT(1) DEFAULT NULL, parent1_profile_id BINARY(16) DEFAULT NULL, parent2_profile_id BINARY(16) DEFAULT NULL, own_profile_id BINARY(16) DEFAULT NULL, INDEX IDX_34DCD17671F741F2 (medical_data_id), INDEX IDX_34DCD176CCFA12B8 (profile_id), INDEX IDX_34DCD1765FE4FA6 (parent1_profile_id), INDEX IDX_34DCD17672609D56 (parent2_profile_id), INDEX IDX_34DCD176C920D4FE (own_profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE leader_role_in_unit (leader_id BINARY(16) NOT NULL, role_in_unit_id BINARY(16) NOT NULL, INDEX IDX_2E1589A973154ED4 (leader_id), INDEX IDX_2E1589A93C3A39CF (role_in_unit_id), PRIMARY KEY(leader_id, role_in_unit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE profile (id BINARY(16) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(20) DEFAULT NULL, gender VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, user_id BINARY(16) NOT NULL, INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE role_in_unit (id BINARY(16) NOT NULL, role VARCHAR(255) NOT NULL, unit_id BINARY(16) NOT NULL, UNIQUE INDEX UNIQ_4ACD96CCF8BD700D (unit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE unit (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, group_id BINARY(16) DEFAULT NULL, INDEX IDX_DCBB0C53FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL, is_first_login TINYINT(1) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, email_address VARCHAR(180) NOT NULL, UNIQUE INDEX UNIQ_8D93D649B08E074E (email_address), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user_person (user_id BINARY(16) NOT NULL, person_id BINARY(16) NOT NULL, INDEX IDX_518ECA4BA76ED395 (user_id), INDEX IDX_518ECA4B217BBB47 (person_id), PRIMARY KEY(user_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD17671F741F2 FOREIGN KEY (medical_data_id) REFERENCES medical_data (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD1765FE4FA6 FOREIGN KEY (parent1_profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD17672609D56 FOREIGN KEY (parent2_profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176C920D4FE FOREIGN KEY (own_profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE leader_role_in_unit ADD CONSTRAINT FK_2E1589A973154ED4 FOREIGN KEY (leader_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE leader_role_in_unit ADD CONSTRAINT FK_2E1589A93C3A39CF FOREIGN KEY (role_in_unit_id) REFERENCES role_in_unit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role_in_unit ADD CONSTRAINT FK_4ACD96CCF8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C53FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id)');
        $this->addSql('ALTER TABLE user_person ADD CONSTRAINT FK_518ECA4BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_person ADD CONSTRAINT FK_518ECA4B217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD17671F741F2');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176CCFA12B8');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD1765FE4FA6');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD17672609D56');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176C920D4FE');
        $this->addSql('ALTER TABLE leader_role_in_unit DROP FOREIGN KEY FK_2E1589A973154ED4');
        $this->addSql('ALTER TABLE leader_role_in_unit DROP FOREIGN KEY FK_2E1589A93C3A39CF');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE role_in_unit DROP FOREIGN KEY FK_4ACD96CCF8BD700D');
        $this->addSql('ALTER TABLE unit DROP FOREIGN KEY FK_DCBB0C53FE54D947');
        $this->addSql('ALTER TABLE user_person DROP FOREIGN KEY FK_518ECA4BA76ED395');
        $this->addSql('ALTER TABLE user_person DROP FOREIGN KEY FK_518ECA4B217BBB47');
        $this->addSql('DROP TABLE groups');
        $this->addSql('DROP TABLE medical_data');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE leader_role_in_unit');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE role_in_unit');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_person');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
