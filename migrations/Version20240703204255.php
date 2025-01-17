<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240703204255 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE answer (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, text VARCHAR(255) NOT NULL, is_correct BOOLEAN NOT NULL, question_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
        $this->addSql('CREATE TABLE question (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE test_result (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, correct BOOLEAN NOT NULL, test_session_id INT NOT NULL, question_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_84B3C63D1A0C5AE6 ON test_result (test_session_id)');
        $this->addSql('CREATE INDEX IDX_84B3C63D1E27F6BF ON test_result (question_id)');
        $this->addSql('CREATE TABLE test_results_answers (test_result_id INT NOT NULL, answer_id INT NOT NULL, PRIMARY KEY(test_result_id, answer_id))');
        $this->addSql('CREATE INDEX IDX_9996796A853A2189 ON test_results_answers (test_result_id)');
        $this->addSql('CREATE INDEX IDX_9996796AAA334807 ON test_results_answers (answer_id)');
        $this->addSql('CREATE TABLE test_session (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, state VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_result ADD CONSTRAINT FK_84B3C63D1A0C5AE6 FOREIGN KEY (test_session_id) REFERENCES test_session (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_result ADD CONSTRAINT FK_84B3C63D1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_results_answers ADD CONSTRAINT FK_9996796A853A2189 FOREIGN KEY (test_result_id) REFERENCES test_result (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_results_answers ADD CONSTRAINT FK_9996796AAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE test_result DROP CONSTRAINT FK_84B3C63D1A0C5AE6');
        $this->addSql('ALTER TABLE test_result DROP CONSTRAINT FK_84B3C63D1E27F6BF');
        $this->addSql('ALTER TABLE test_results_answers DROP CONSTRAINT FK_9996796A853A2189');
        $this->addSql('ALTER TABLE test_results_answers DROP CONSTRAINT FK_9996796AAA334807');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE test_result');
        $this->addSql('DROP TABLE test_results_answers');
        $this->addSql('DROP TABLE test_session');
    }
}
