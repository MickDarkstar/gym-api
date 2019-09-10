<?php
final class EntryController extends BaseController
{
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new EntryRepository();
    }

    public static function New()
    {
        return new self;
    }

    public function All()
    {
        parent::Authorize();

        $result = $this->service->getEntriesByUser(parent::$currentUser);
        echo Response::Ok("All Entries", $result);
    }

    public function Current()
    {
        parent::Authorize();

        $currentEntry = $this->service->getTodaysEntry(parent::$currentUser);
        if ($currentEntry == null) {
            $currentEntry = $this->service->createEntry(parent::$currentUser);
            if ($currentEntry instanceof Entry == false) {
                echo Response::InternalServerError("Could not get or create todays entry", $currentEntry);
            }
        }
        echo Response::Ok("Current entry", $currentEntry);
    }

    public function CreateEntry()
    {
        parent::Authorize();

        $result = $this->service->createEntry(parent::$currentUser);
        echo ($result > 0) ?
            Response::Created("Entry created", $result)
            : Response::Created("Entry could not be created", $result);
    }

    public function UpdateEntry()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $model = $this->service->getById($data->id);
        if ($model === null) {
            echo Response::Warning("Entry does not exist");
        } else {
            $model->Update(
                $data->comment
            );
            $result = $this->service->updateEntry($model);

            echo ($result) ? Response::Ok("Updated Entry info", $result) : Response::Ok("Could not update Entry info", $result);
        }
    }

    public function CreateEntryDetail()
    {
        parent::Authorize();
        $data = parent::HttpRequestInput();

        $model = EntryDetail::Create(
            $data->entryId,
            $data->exercise,
            $data->weight,
            $data->reps,
            $data->sets,
            $data->date,
            $data->comment
        );
        $result = $this->service->createEntryDetail($model);
        echo ($result > 0) ?
            Response::Created("EntryDetail created", $result)
            : Response::Created("EntryDetail could not be created", $result);
    }

    public function UpdateEntryDetail()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $model = $this->service->getById($data->id);
        if ($model === null) {
            echo Response::Warning("Entry does not exist");
        } else if ($model instanceof EntryDetail) {
            $model->Update(
                $data->entryId,
                $data->exercise,
                $data->weight,
                $data->reps,
                $data->sets,
                $data->date,
                $data->comment
            );
            $result = $this->service->updateEntryDetail($model);

            echo ($result) ? Response::Ok("Updated Entry info", $result) : Response::InternalServerError("Could not update Entry info", $result);
        }
    }

    public function DeleteEntry()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $model = $this->service->getEntryById($data->id);
        if ($model instanceof Entry == false) {
            echo Response::InternalServerError("Entry does not exist, can´t delete");
        } else {
            $result = $this->service->deleteEntry($model);
            echo ($result) ? Response::Ok("Deleted Entry", $result)
                : Response::InternalServerError("Could not delete Entry", $result);
        }
    }

    public function DeleteEntryDetail()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $model = $this->service->getEntryDetailById($data->id);
        if ($model instanceof EntryDetail == false) {
            echo Response::InternalServerError("EntryDetail does not exist, can´t delete");
        } else {
            $result = $this->service->deleteEntryDetail($model);
            echo ($result) ? Response::Ok("Deleted EntryDetail", $result)
                : Response::InternalServerError("Could not delete EntryDetail", $result);
        }
    }
}
