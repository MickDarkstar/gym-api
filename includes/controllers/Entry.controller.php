<?php
final class EntryController extends BaseController
{
    private $service;
    private $exerciseService;

    public function __construct()
    {
        parent::__construct();
        $this->service = new EntryRepository();
        $this->exerciseService = new ExerciseService();
    }

    public static function New()
    {
        return new self;
    }

    public function AllEntries()
    {
        parent::Authorize();

        $result = $this->service->getEntriesByUser(parent::$currentUser);
        echo ApiResponse::Ok("All Entries", $result);
    }

    public function Current()
    {
        parent::Authorize();

        $currentEntry = $this->service->getTodaysEntry(parent::$currentUser);
        if ($currentEntry == null) {
            $currentEntry = $this->service->createEntry(parent::$currentUser);
            if ($currentEntry instanceof Entry == false) {
                echo ApiResponse::InternalServerError("Could not get or create todays entry", $currentEntry);
                exit();
            }
        }
        echo ApiResponse::Ok("Current entry", $currentEntry);
    }

    public function CreateEntry()
    {
        parent::Authorize();
        // Unused, maybe implement when supporting more than one entry per date
        $result = $this->service->createEntry(parent::$currentUser);
        echo ($result > 0)
            ? ApiResponse::Created("Entry created", $result)
            : ApiResponse::Created("Entry could not be created", $result);
    }

    public function UpdateEntry()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $model = $this->service->getEntryById($data->id);
        if ($model === null) {
            echo ApiResponse::Warning("Entry does not exist");
        } else if ($model instanceof Entry){
            $model->Update(
                $data->comment
            );
            $result = $this->service->updateEntry($model);

            echo ($result) ? ApiResponse::Ok("Updated Entry info", $result) : ApiResponse::Ok("Could not update Entry info", $result);
        }
    }

    public function CreateEntryDetail()
    {
        $user = parent::Authorize();
        $data = parent::HttpRequestInput();

        $exerciseResponse = $this->exerciseService->getById($data->exerciseId);
        parent::HandleValidationErrors($exerciseResponse);

        if ($exerciseResponse instanceof Exercise === false) {
            echo ApiResponse::InternalServerError("Incorrect exercise id, it may have been removed");
            die();
        }

        $todaysEntry = $this->GetOrCreateTodaysEntry($user);
        if ($todaysEntry instanceof Entry === false) {
            echo ApiResponse::InternalServerError("Error creating todays workout entry");
            die();
        }

        $model = EntryDetail::Create(
            $todaysEntry->id,
            $exerciseResponse,
            $user
        );

        $validation = EntryRepository::validateCreateEntryDetail($model);
        parent::HandleValidationErrors($validation);

        $result = $this->service->createEntryDetail($model);

        echo ($result > 0)
            ? ApiResponse::Created("EntryDetail created", $result)
            : ApiResponse::Created("EntryDetail could not be created", $result);
    }

    public function UpdateEntryDetail()
    {
        $currentUser = parent::Authorize();

        $data = parent::HttpRequestInput();
        $model = $this->service->getEntryDetailById($data->id);
        if ($model === null) {
            echo ApiResponse::Warning("Entry does not exist");
        } else if ($model instanceof EntryDetail) {
            $model->Update(
                $currentUser,
                $data->weight,
                $data->reps,
                $data->rest,
                $data->sets,
                $data->date,
                $data->comment
            );
            $result = $this->service->updateEntryDetail($model);

            echo ($result) ?
                ApiResponse::Ok("Updated Entry info", $result)
                :
                ApiResponse::InternalServerError("Could not update Entry info", $result);
        }
    }

    public function DeleteEntry()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $model = $this->service->getEntryById($data->id);
        if ($model instanceof Entry == false) {
            echo ApiResponse::InternalServerError("Entry does not exist, can´t delete");
        } else {
            $result = $this->service->deleteEntry($model);
            echo ($result)
                ? ApiResponse::Ok("Deleted Entry", $result)
                : ApiResponse::InternalServerError("Could not delete Entry", $result);
        }
    }

    public function DeleteEntryDetail()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $model = $this->service->getEntryDetailById($data->id);
        if ($model instanceof EntryDetail == false) {
            echo ApiResponse::InternalServerError("EntryDetail does not exist, can´t delete");
        } else {
            $result = $this->service->deleteEntryDetail($model);
            echo ($result) ? ApiResponse::Ok("Deleted EntryDetail", $result)
                : ApiResponse::InternalServerError("Could not delete EntryDetail", $result);
        }
    }

    private function GetOrCreateTodaysEntry(AppUser $user)
    {
        $todaysEntry = $this->service->getTodaysEntry(parent::$currentUser);
        if ($todaysEntry instanceof Entry === false) {
            $todaysEntry = $this->service->createEntry($user);
        }
        return $todaysEntry;
    }
}
