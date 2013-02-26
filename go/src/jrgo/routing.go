package jrgo

import (
	"fmt"
	"log"
	"reflect"
	"strings"
)

type controllerMap struct {
	controllerName string
	controllerType reflect.Type
}
type Routing struct {
	JrBase
	maps []*controllerMap
}

/*
type RoutingInterface interface {
	Register(action string, c ControllerInterface)
	Call(path string)
}
*/
func init() {
	//JrRouting.Register("gii.default", c)
}

func (this *Routing) Register(name string, c ControllerInterface) {
	t := reflect.Indirect(reflect.ValueOf(c)).Type()
	m := &controllerMap{strings.ToLower(name), t}

	fmt.Println("->add action:", name)
	this.userMaps = append(this.userMaps, m)
}

/** 
 * 根据请求URL的路径，调用相应方法
 */
func (this *Routing) Call(path string) {

	var (
		moduleMatch string
		moduleType  reflect.Type
		action      string
		find        bool
	)
	params, length := this.ParsePath(path)

	if length < 2 {
		moduleMatch = params[0] + ".default"
		params[1], params[2] = "index", "index"
	} else {
		moduleMatch = params[0] + "." + strings.ToLower(params[1])
		if length < 3 {
			params[2] = "index"
		}
	}
	fmt.Println("m.action:", params, length)

	for _, m := range this.userMaps {
		if m.controllerName == params[0] {
			moduleType = m.controllerType
			action = params[1]
			find = true
			break
		} else if m.controllerName == moduleMath {
			moduleType = m.controllerType
			action = params[2])
			find = true
			break
		}
	}

	if find == true {
		vc := reflect.New(moduleType)
		method := vc.MethodByName(strings.Title(action))
		if method.IsValid() == true {
			in := make([]reflect.Value, 0)
			method.Call(in)
		} else {
			log.Fatal("Call to undefined action: ", action)
		}
	} else {
		log.Fatal("Call to undefined controller or module: ", params[0])
	}
}

/**
 * 解析路径，分离参数及计算参数个数
 */
func (this *Routing) ParsePath(path string) ([]string, int) {
	temp := strings.Split(path, "/")
	var params []string

	for _, v := range temp {
		if strings.Trim(v, " ") != "" {
			params = append(params, v)
		}
	}

	length := len(params)

	if length == 0 {
		params[0], length = "site", 1
	}
	params[0] = strings.ToLower(params[0])

	return params, length
}
