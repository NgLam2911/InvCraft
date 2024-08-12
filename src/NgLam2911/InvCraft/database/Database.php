<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\database;

use Generator;
use NgLam2911\InvCraft\InvCraft;
use NgLam2911\InvCraft\libs\_522e88ace0fcd4c0\SOFe\AwaitGenerator\Await;
use NgLam2911\InvCraft\crafting\Recipe;
use NgLam2911\InvCraft\utils\NbtHelper;
use NgLam2911\InvCraft\libs\_522e88ace0fcd4c0\poggit\libasynql\DataConnector;
use NgLam2911\InvCraft\libs\_522e88ace0fcd4c0\poggit\libasynql\libasynql;
use NgLam2911\InvCraft\database\DatabaseStmts as Stmts;

final class     Database {

    private DataConnector $database;
    private BinaryStringParser $parser;

    public function __construct(
        protected InvCraft $plugin
    ){
        Await::f2c(function () : Generator{
            yield from $this->asyncInit();
        });
        //TODO: Handle errors
    }

    public function asyncInit() : Generator {
        $configurations = $this->plugin->getConfig()->get("database");
        $type = $configurations["type"];
        $this->parser = BinaryStringParser::fromDatabase($type);
        $this->database = libasynql::create($this->plugin, $configurations, [
            "sqlite" => "sql/sqlite.sql",
            "mysql" => "sql/mysql.sql"
        ]);
        yield from $this->database->asyncGeneric(Stmts::INIT);
    }

    public function asyncLoad() : Generator{
        $result = yield from $this->database->asyncSelect(Stmts::LOAD);
        foreach ($result as $row){
            $data = $this->parser->decode($row["data"]);
            $nbt = NbtHelper::decompressCompoundTag($data);
            $recipe = Recipe::nbtDeserialize($nbt);
            $this->plugin->getRecipeManager()->addRecipe($recipe, false);
        }
    }

    public function asyncAdd(Recipe $recipe) : Generator{
        $nbt = $recipe->nbtSerialize();
        $data = NbtHelper::compressCompoundTag($nbt);
        yield from $this->database->asyncInsert(Stmts::ADD, [
            "name" => $recipe->getName(),
            "data" => $this->parser->encode($data)
        ]);
    }

    public function asyncUpdate(Recipe $recipe) : Generator{
        $nbt = $recipe->nbtSerialize();
        $data = NbtHelper::compressCompoundTag($nbt);
        yield from $this->database->asyncChange(Stmts::UPDATE, [
            "name" => $recipe->getName(),
            "data" => $this->parser->encode($data)
        ]);
    }

    public function asyncDelete(string $name) : Generator{
        yield from $this->database->asyncChange(Stmts::DELETE, ["name" => $name]);
    }

    public function close() : void{
        if (isset($this->database)){
            $this->database->waitAll();
            $this->database->close();
        }
    }
}