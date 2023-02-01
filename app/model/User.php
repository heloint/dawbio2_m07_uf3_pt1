<?php
/* User class representing a model of the MVC architecture
 * corresponding to the User related operations.
 * @author Dániel Májer
 * */

namespace proven\store\model;

class User
{
    private int $id;
    private ?string $username;
    private ?string $password;
    private ?string $firstname;
    private ?string $lastname;
    private ?string $role;

    public function __construct(
        int $id = 0,
        string $username = null,
        string $password = null,
        string $firstname = null,
        string $lastname = null,
        string $role = null
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->role = $role;
    }

    /* Get ID of object.
     * @return int
     * */
    public function getId(): int
    {
        return $this->id;
    }

    /* Get Username of object.
     * @return string | null
     * */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /* Get password of object.
     * @return string | null
     * */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /* Get firstname of object.
     * @return string | null
     * */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /* Get lastname of object.
     * @return string | null
     * */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /* Get role of object.
     * @return string | null
     * */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /* Set id of object.
     * @param id int
     * @return void
     * */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /* Set username of object.
     * @param username string | null
     * @return void
     * */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /* Set password of object.
     * @param password string | null
     * @return void
     * */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /* Set firstname of object.
     * @param firstname string | null
     * @return void
     * */
    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /* Set lastname of object.
     * @param lastname string | null
     * @return void
     * */
    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /* Set role of object.
     * @param role string | null
     * @return void
     * */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    /* String representation of the class.
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "User{[id=%d][username=%s][password=%s][firstname=%s][lastname=%s][role=%s]}",
            $this->id,
            $this->username,
            $this->password,
            $this->firstname,
            $this->lastname,
            $this->role
        );
    }
}
