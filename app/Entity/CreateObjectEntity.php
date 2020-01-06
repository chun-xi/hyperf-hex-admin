<?php


namespace App\Entity;

/**
 * 创建对象实体
 * Class CreateObjectEntity
 * @package App\Entity
 */
class CreateObjectEntity
{
    /**
     * 创建模型
     * @var string
     */
    private string $model;

    /**
     * 数据结构体
     * @var array
     */
    private array $map;

    /**
     * 中间表
     * @var array
     */
    private array $middle = [];

    /**
     * 创建时间
     * @var string
     */
    private string $createDate = '';


    /**
     * 更新时间
     * @var string
     */
    private string $updateDate = '';

    /**
     * @return string
     */
    public function getUpdateDate(): string
    {
        return $this->updateDate;
    }

    /**
     * @param string $dateKey
     * @return CreateObjectEntity
     */
    public function setUpdateDate(string $dateKey): self
    {
        $this->updateDate = $dateKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreateDate(): string
    {
        return $this->createDate;
    }

    /**
     * @param string $dateKey
     * @return CreateObjectEntity
     */
    public function setCreateDate(string $dateKey): self
    {
        $this->createDate = $dateKey;
        return $this;
    }


    /**
     * @param string $key
     * @return array
     */
    public function getMiddle(string $key)
    {
        return $this->middle[$key];
    }

    /**
     * @param string $key
     * @param string $middle
     * @param string $foreignKey
     * @param string $localKey
     * @return CreateObjectEntity
     */
    public function setMiddle(string $key, string $middle, string $foreignKey, string $localKey): self
    {
        $this->middle[$key] = [
            'middle' => $middle,
            'foreignKey' => $foreignKey,
            'localKey' => $localKey
        ];
        return $this;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param string $model
     * @return CreateObjectEntity
     */
    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        return $this->map;
    }

    /**
     * @param array $map
     * @return CreateObjectEntity
     */
    public function setMap(array $map): self
    {
        foreach ($map as $key => $value) {
            if ($value === '') {
                unset($map[$key]);
            }
        }
        $this->map = $map;
        return $this;
    }

}