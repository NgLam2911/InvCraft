-- #! sqlite
-- #{ invcraft
-- #    { init
CREATE TABLE IF NOT EXISTS invcraft_recipes (
    name TEXT PRIMARY KEY NOT NULL,
    data BLOB NOT NULL
);
-- #    }
-- #    { load
SELECT * FROM invcraft_recipes;
-- #    }
-- #    { add
-- #        :name txt
-- #        :data string
INSERT OR REPLACE INTO invcraft_recipes (name, data) VALUES (:name, :data);
-- #    }
-- #    { update
-- #        :name txt
-- #        :data string
UPDATE invcraft_recipes SET data = :data WHERE name = :name;
-- #    }
-- #    { delete
-- #        :name txt
DELETE FROM invcraft_recipes WHERE name = :name;
-- #    }
-- #}