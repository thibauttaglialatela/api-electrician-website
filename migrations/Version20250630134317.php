<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250630134317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certification ADD image_id INT NOT NULL');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D753DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6C3C6D753DA5256D ON certification (image_id)');
        $this->addSql('ALTER TABLE image ADD work_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FBB3453DB FOREIGN KEY (work_id) REFERENCES work (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_C53D045FBB3453DB ON image (work_id)');
        $this->addSql('ALTER TABLE partner ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E163DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_312B3E163DA5256D ON partner (image_id)');
        $this->addSql('ALTER TABLE prestation ADD image_id INT NOT NULL');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51C88FAD3DA5256D ON prestation (image_id)');
        $this->addSql('ALTER TABLE work ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E688019EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_534E688019EB6921 ON work (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D753DA5256D');
        $this->addSql('DROP INDEX UNIQ_6C3C6D753DA5256D ON certification');
        $this->addSql('ALTER TABLE certification DROP image_id');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E688019EB6921');
        $this->addSql('DROP INDEX IDX_534E688019EB6921 ON work');
        $this->addSql('ALTER TABLE work DROP client_id');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FAD3DA5256D');
        $this->addSql('DROP INDEX UNIQ_51C88FAD3DA5256D ON prestation');
        $this->addSql('ALTER TABLE prestation DROP image_id');
        $this->addSql('ALTER TABLE partner DROP FOREIGN KEY FK_312B3E163DA5256D');
        $this->addSql('DROP INDEX IDX_312B3E163DA5256D ON partner');
        $this->addSql('ALTER TABLE partner DROP image_id');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FBB3453DB');
        $this->addSql('DROP INDEX IDX_C53D045FBB3453DB ON image');
        $this->addSql('ALTER TABLE image DROP work_id');
    }
}
