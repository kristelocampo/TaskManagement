<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525093218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_task DROP FOREIGN KEY FK_6BEF133DB8E08577');
        $this->addSql('ALTER TABLE project_task DROP FOREIGN KEY FK_6BEF133D6C1197C9');
        $this->addSql('DROP TABLE project_task');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_task (id INT AUTO_INCREMENT NOT NULL, project_id_id INT DEFAULT NULL, task_id_id INT DEFAULT NULL, INDEX IDX_6BEF133D6C1197C9 (project_id_id), INDEX IDX_6BEF133DB8E08577 (task_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE project_task ADD CONSTRAINT FK_6BEF133DB8E08577 FOREIGN KEY (task_id_id) REFERENCES tasks (id)');
        $this->addSql('ALTER TABLE project_task ADD CONSTRAINT FK_6BEF133D6C1197C9 FOREIGN KEY (project_id_id) REFERENCES projects (id)');
    }
}
