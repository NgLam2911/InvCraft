-- #! sqlite
-- #{ invcraft
-- #    { init
CREATE TABLE IF NOT EXISTS invcraft_recipes (
    name TEXT PRIMARY KEY NOT NULL,
    data BLOB NOT NULL
);
-- #    }
-- #    { load
SELECT name, HEX(data) AS data FROM invcraft_recipes;
-- #    }
-- #    { add
-- #        :name string
-- #        :data string
INSERT OR REPLACE INTO invcraft_recipes (name, data) VALUES (:name, X:data);
-- #    }
-- #    { update
-- #        :name string
-- #        :data string
UPDATE invcraft_recipes SET data = :data WHERE name = :name;
-- #    }
-- #    { delete
-- #        :name string
DELETE FROM invcraft_recipes WHERE name = :name;
-- #    }
-- #}