<?php

namespace SumoCoders\Teamleader\Meetings;

use SumoCoders\Teamleader\Crm\Contact;

class MeetingContactAttendee
{
    /**
     * @var int
     */
    private $meeting;

    /**
     * @var int
     */
    private $contact;

    /**
     * @return int
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * @param int $meeting
     */
    public function setMeeting($meeting)
    {
        if ($meeting instanceof Meeting) {
            $meeting = $meeting->getId();
        }

        $this->meeting = $meeting;
    }

    /**
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param int $contact
     */
    public function setContact($contact)
    {
        if ($contact instanceof Contact) {
            $contact = $contact->getId();
        }

        $this->contact = $contact;
    }

    /**
     * This method will convert a meeting to an array that can be used for an API-request
     *
     * @return array
     */
    public function toArrayForApi()
    {
        $return = array();

        if ($this->getMeeting()) {
            $return['meeting_id'] = $this->getMeeting();
        }
        if ($this->getContact()) {
            $return['contact_id'] = $this->getContact();
        }

        return $return;
    }
}
